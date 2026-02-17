# Resource Upload Feature Implementation

## Task: After uploading a new resource by the admin, it should save in the database and reflect both in the admin and agent views as downloadable items, and the agent should get in-system communication on the same.

### Steps:

- [x] 1. Create database migration for resources table
- [ ] 2. Create Resource model (app/models/Resource.php)
- [ ] 3. Update AgentController with resource management methods
- [ ] 4. Update AgentDashboardController with resource viewing/download
- [ ] 5. Create admin resources view (resources/views/admin/resources.php)
- [ ] 6. Create/update agent resources view (resources/views/agent/resources.php)
- [ ] 7. Update Router with resource routes
- [ ] 8. Create migration runner script
- [ ] 9. Test the implementation

### Files to Create/Modify:

**New Files:**
- database/migrations/011_add_resources_table.sql
- app/models/Resource.php
- resources/views/admin/resources.php
- resources/views/agent/resources.php
- run_migration_011.php

**Modified Files:**
- app/controllers/AgentController.php
- app/controllers/AgentDashboardController.php
- app/core/Router.php
