# Resource Management System Implementation

## Overview
A complete resource management system has been implemented that allows administrators to upload resources (files) that are automatically saved to the database and made available as downloadable items for agents. The system includes in-system communication to notify agents when new resources are uploaded.

## Features Implemented

### 1. Database Schema (Migration 011)
- **resources table**: Stores resource metadata
  - id, title, description, file_name, file_path, original_name
  - file_size, mime_type, category, uploaded_by
  - is_active, download_count, created_at, updated_at
  
- **resource_downloads table**: Tracks download history
  - id, resource_id, user_id, ip_address, user_agent, downloaded_at

### 2. Resource Model (app/models/Resource.php)
Full CRUD operations with additional features:
- `create()` - Create new resource record
- `getAll()` - Get all resources with optional filtering
- `getByCategory()` - Get resources by category
- `getById()` - Get single resource by ID
- `update()` - Update resource metadata
- `delete()` - Delete resource and file
- `incrementDownloadCount()` - Track downloads
- `recordDownload()` - Log download history
- `getDownloadHistory()` - Get download statistics
- `getGroupedByCategory()` - Group resources by category
- `formatFileSize()` - Human-readable file sizes
- `getCategoryLabel()` - Category display names
- `getCategoryIcon()` - Category icons

### 3. Admin Controller Methods (app/controllers/AgentController.php)
- `resources()` - Display resource management page
- `uploadResource()` - Handle file uploads with validation
- `deleteResource()` - Delete resources
- `downloadResource()` - Download resources
- `exportResources()` - Export resource list to CSV

**File Upload Validation:**
- Max file size: 10MB
- Allowed types: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, JPEG, PNG, ZIP
- Secure file storage in `storage/uploads/resources/`
- Unique filename generation to prevent conflicts

### 4. Agent Dashboard Controller Methods (app/controllers/AgentDashboardController.php)
- `resources()` - Display available resources to agents
- `downloadResource()` - Handle agent downloads with tracking

### 5. Admin View (resources/views/admin/resources.php)
Features:
- Resource statistics dashboard
- Category filtering
- Upload modal with drag-and-drop
- Resource cards with download/delete actions
- Export to CSV functionality
- Responsive grid layout

### 6. Agent View (resources/views/agent/resources.php)
Features:
- Category-based resource display
- Download statistics
- "NEW" badge for recent uploads (within 7 days)
- Responsive design
- Empty state handling

### 7. In-System Communication
When an admin uploads a resource, all agents automatically receive an in-app notification:
- **Title**: "New Resource Available: [Resource Title]"
- **Message**: A new [category] resource "[title]" has been uploaded. Click to view and download.
- **Action URL**: /agent/resources
- **Action Text**: View Resources

## File Structure
```
database/migrations/011_add_resources_table.sql  - Database schema
app/models/Resource.php                           - Resource model
app/controllers/AgentController.php               - Admin resource management
app/controllers/AgentDashboardController.php      - Agent resource viewing
resources/views/admin/resources.php               - Admin interface
resources/views/agent/resources.php               - Agent interface
app/core/Router.php                               - Route definitions
run_migration_011.php                           - Migration runner
storage/uploads/resources/                        - File storage directory
```

## Routes Added
```
# Admin Routes
GET    /admin/agents/resources              - View resources
POST   /admin/agents/resources/upload       - Upload resource
GET    /admin/agents/resources/export       - Export CSV
GET    /admin/agents/resources/download/{id} - Download resource
POST   /admin/agents/resources/delete/{id}  - Delete resource

# Agent Routes
GET    /agent/resources                     - View resources
GET    /agent/resources/download/{id}     - Download resource
```

## Usage Instructions

### For Administrators:
1. Navigate to `/admin/agents/resources`
2. Click "Upload Resource" button
3. Fill in:
   - Resource Title (required)
   - Description (optional)
   - Category (required)
   - File (required, max 10MB)
4. Click "Upload Resource"
5. All agents will be automatically notified

### For Agents:
1. Navigate to `/agent/resources` or click "Resources" in the menu
2. Browse resources by category
3. Click "Download" on any resource
4. Download history is tracked automatically

## Security Features
- CSRF token validation on all forms
- File type validation (whitelist approach)
- File size limits
- Secure file storage outside web root
- User authentication required for all actions
- Download tracking for audit purposes

## Categories Available
1. **Marketing Materials** - Flyers, brochures, promotional content
2. **Training Documents** - Guides, manuals, training resources
3. **Policy Documents** - Terms, conditions, policy information
4. **Forms** - Application and registration forms
5. **Other** - Additional helpful materials

## Next Steps / Future Enhancements
1. Add resource versioning
2. Implement resource expiration dates
3. Add resource ratings/reviews by agents
4. Enable resource search functionality
5. Add bulk upload capability
6. Implement resource analytics dashboard
7. Add email notifications in addition to in-app notifications
8. Enable resource sharing between agents

## Testing
Run the migration:
```bash
php run_migration_011.php
```

Access the admin panel:
```
http://localhost/Shena/admin/agents/resources
```

Access the agent panel:
```
http://localhost/Shena/agent/resources
