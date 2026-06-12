Phase 1,Authentication & Foundation,"Registration, Login, Google OAuth, Forgot Password, Logout, Session & Security",Highest,Medium -> DONE

Phase 2,Core Modules (Clients + Pipeline),"Clients CRUD, Pipeline Kanban logic, Phase movement",High,Medium-High

Phase 3,Quotation & Invoice System,"Create Quotation + Items, Convert to Invoice, Invoice management",High,High

Phase 4,Accounting Module,"Cashbook, Suggested entries from invoices, Basic reports",Medium,Medium

Phase 5,Support & Retention,"Tickets system, Review collection",Medium,Medium

Phase 6,Settings & Configuration,"Product Catalog, User Management, Email Templates structure",Medium,
Low-Medium

Phase 7,"Security, Polish & Optimization","Validation, Rate limiting, Logging, Performance, Multi-tenancy enforcement",High,Medium

PHASE 2 DEATIS:

Phase 2 - Sub Phases (Small, Manageable & Error-Free)
2A: Database & Model Layer (Clients) DONE

Update clients table (above alterations)
Create/Update app/Models/Client.php (extends Core\Model)
Relationships: belongsTo Tenant, hasMany PipelineOpportunity, hasMany Project, etc.
Soft delete, audit fields, validation rules
getFullProfile() method

Update TenantUsage tracking for max_clients enforcement

Priority: Highest (foundation)

2B: ClientController CRUD Base

app/Controllers/ClientController.php
Methods: index(), create(), store(), show(), edit(), update(), destroy()
Multi-tenancy enforcement ($this->currentTenantId)
max_clients check in store() (Plan limit থেকে)

2C: Client Full Profile View + Form

resources/View/clients/index.php + modal forms
Rich profile form (all fields + image upload)
Google Maps embed for location
Social profile links (clickable)
Card + Table toggle view (existing UI improve করবো)

2D: Pipeline Model & Backend Logic

app/Models/PipelineOpportunity.php
Phase movement logic (1→5)
Validation (can't move backward without confirmation, etc.)
Link with Client + Project

2E: Pipeline Kanban Backend Integration

app/Controllers/PipelineController.php
Drag & Drop phase update via AJAX
Smart confirmation modal (phase change rules)
Real-time data from DB (replace localStorage)

2F: Integration & Polish

Clients ↔ Pipeline sync (new client → auto Phase 1)
Search + Advanced Filter (status, source, tags, next_followup)
Audit log on every action
Permission check (owner/admin only for certain actions)

