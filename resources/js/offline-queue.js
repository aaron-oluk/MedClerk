const STORAGE_KEY = 'medclerk.logbook-queue';

function readQueue() {
    try {
        return JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
    } catch (e) {
        return [];
    }
}

function writeQueue(queue) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(queue));
    window.dispatchEvent(new CustomEvent('medclerk:queue-updated', { detail: { count: queue.length } }));
}

function uuid() {
    if (window.crypto && window.crypto.randomUUID) {
        return window.crypto.randomUUID();
    }

    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, (c) => {
        const r = (Math.random() * 16) | 0;
        const v = c === 'x' ? r : (r & 0x3) | 0x8;
        return v.toString(16);
    });
}

export const OfflineQueue = {
    add(entry) {
        const queue = readQueue();
        const record = { ...entry, client_uuid: uuid(), queued_at: new Date().toISOString() };
        queue.push(record);
        writeQueue(queue);
        return record;
    },

    all() {
        return readQueue();
    },

    count() {
        return readQueue().length;
    },

    async sync() {
        const queue = readQueue();
        if (queue.length === 0 || !navigator.onLine) {
            return { synced: 0, remaining: queue.length };
        }

        const remaining = [];
        let synced = 0;

        for (const entry of queue) {
            try {
                await window.axios.post('/logbook-entries/sync', entry);
                synced += 1;
            } catch (error) {
                if (error.response && error.response.status >= 400 && error.response.status < 500 && error.response.status !== 419) {
                    // Rejected by validation, not a connectivity issue. Drop it rather than retry forever.
                    synced += 1;
                } else {
                    remaining.push(entry);
                }
            }
        }

        writeQueue(remaining);

        return { synced, remaining: remaining.length };
    },
};

window.MedClerkOfflineQueue = OfflineQueue;

window.addEventListener('online', () => {
    OfflineQueue.sync();
});

if (navigator.onLine) {
    OfflineQueue.sync();
}
