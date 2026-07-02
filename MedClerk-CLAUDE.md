# CLAUDE.md — MedClerk

This file gives Claude Code persistent context for working on MedClerk across sessions.

## Project Overview

MedClerk is a clinical education and competency assessment platform for medical students. It combines an interactive library of clinical signs and examination techniques with digital, curriculum aligned assessment tools, giving lecturers a way to standardize scoring and giving students structured feedback on their clinical rotations. It is built for multi institution scaling and formal procurement, with accreditation grade security and audit trails built into its design.

## Tech Stack

- Mobile frontend: React Native, with offline sync
- Web frontend: React.js, for administrative and desktop use
- Backend: RESTful API on Node.js and Express, structured to be microservices ready as usage scales
- Database: PostgreSQL, normalized schema, designed for multi institution deployment from the outset

## Core Domain Entities

- Institutions: central table enabling deployment across multiple universities
- Users: role based access covering students, lecturers, admins, and superadmins, each tied to an institution and department
- Programs and Cohorts: program specific curricula and student batch tracking over time
- Clinical_Systems and Clinical_Signs: the core educational content library of examination systems and signs, including interpretation and diagnostic relevance
- Skills: structured, step by step tutorials with procedure steps stored as JSON and mapped to CBME competency standards
- Assessments: curriculum aligned scoring linked to a specific student, skill, and clinical rotation
- Rotations and Logbook_Entries: clerkship placements and structured clinical encounter records, including findings stored as JSON
- Feedback: structured feedback tied to strengths, areas to improve, and a follow up date
- Analytics_Snapshots: materialized view powering student and cohort performance dashboards
- Audit_Logs: full audit trail supporting institutional accreditation requirements
- Tags and Resource_Tags: polymorphic tagging supporting search and future recommendation features

## Core Modules

- Clinical learning library (multimedia coverage of clinical systems, signs, and diagnostic relevance)
- Digital logbook (real time recording of clinical encounters and procedures)
- Assessment engine (curriculum aligned scoring tied to skills, rotations, and assessors)
- Feedback and reflection (structured feedback plus student self reflection tools)
- Analytics and reporting (cohort and individual performance dashboards)
- Security and access control (role based permissions, encryption, full audit logging)

## Design Principles

- Treat assessment and logbook data as an authoritative academic record. Every write to Assessments, Logbook_Entries, or Feedback should be traceable through Audit_Logs.
- The schema is institution agnostic by design. New features should not assume a single institution or single curriculum version.
- Core learning content and logbook entry forms must remain usable offline, syncing automatically once connectivity is restored, with no loss of recorded data.
- Index frequently queried fields such as student_id and date to keep dashboards and reports performant as data volume grows across institutions.

## Conventions

- Follow RESTful API design for all backend endpoints, structured so individual domains (assessments, logbook, content library) can later be split into separate services if needed
- Use JSON columns only where the underlying data is genuinely variable in shape (for example procedure_steps or findings); use normalized tables and foreign keys everywhere else
- Role based access checks should happen at the API layer, not only in the frontend

## Formatting Rules

- Do not use hyphens, em dashes, or en dashes in generated prose, UI copy, comments, or documentation. Use plain words or commas instead.
- Hyphens are acceptable where technically required, such as CSS utility class names, npm package names, and kebab case file or route names.
- Do not introduce or reference any company or vendor name in user facing copy unless explicitly asked to.

## Status

Proposed platform for Kabale University's medical program, scoped at a product and architecture level, designed from the outset for later expansion to other universities and allied health programs.
