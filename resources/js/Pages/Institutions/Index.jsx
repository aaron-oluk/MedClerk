import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { Head, useForm } from '@inertiajs/react';

export default function Index({ institutions }) {
    const { data, setData, post, errors, processing, reset } = useForm({
        name: '',
        country: '',
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('institutions.store'), {
            onSuccess: () => reset(),
        });
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Institutions
                </h2>
            }
        >
            <Head title="Institutions" />

            <div className="py-12">
                <div className="mx-auto max-w-5xl space-y-6 sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            <table className="w-full text-left text-sm">
                                <thead>
                                    <tr className="border-b border-gray-200 dark:border-gray-700">
                                        <th className="py-2">Name</th>
                                        <th className="py-2">Country</th>
                                        <th className="py-2">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {institutions.map((institution) => (
                                        <tr
                                            key={institution.id}
                                            className="border-b border-gray-100 dark:border-gray-700"
                                        >
                                            <td className="py-2">{institution.name}</td>
                                            <td className="py-2">{institution.country ?? 'Not set'}</td>
                                            <td className="py-2">{institution.status}</td>
                                        </tr>
                                    ))}
                                    {institutions.length === 0 && (
                                        <tr>
                                            <td colSpan={3} className="py-4 text-gray-500 dark:text-gray-400">
                                                No institutions yet.
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <form onSubmit={submit} className="space-y-6 p-6">
                            <h3 className="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Add an institution
                            </h3>

                            <div>
                                <InputLabel htmlFor="name" value="Name" />
                                <TextInput
                                    id="name"
                                    className="mt-1 block w-full"
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    required
                                />
                                <InputError className="mt-2" message={errors.name} />
                            </div>

                            <div>
                                <InputLabel htmlFor="country" value="Country" />
                                <TextInput
                                    id="country"
                                    className="mt-1 block w-full"
                                    value={data.country}
                                    onChange={(e) => setData('country', e.target.value)}
                                />
                                <InputError className="mt-2" message={errors.country} />
                            </div>

                            <PrimaryButton disabled={processing}>Save</PrimaryButton>
                        </form>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
