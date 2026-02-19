# Copilot Instructions for Shena Companion Welfare Association

This is a PHP web application for managing a welfare association that provides funeral services and burial expense coverage. The system uses a custom MVC architecture with the following structure:

## Project Structure
- **app/controllers/**: Handle HTTP requests and business logic
- **app/models/**: Database interaction and data management
- **app/services/**: Business logic services (Email, SMS, Payment)
- **app/core/**: Framework core classes (Router, Database, BaseController, BaseModel)
- **app/helpers/**: Utility functions and helpers
- **resources/views/**: Template files for rendering HTML
- **config/**: Application configuration
- **database/**: SQL schema and migrations

## Key Features
- Member registration and management
- M-Pesa payment integration
- Claims processing system
- Email and SMS notifications
- Role-based access control (Member, Manager, Super Admin)
- Document upload and management
- Financial reporting and analytics

## Coding Standards
- Follow PSR-1 and PSR-2 coding standards
- Use meaningful variable and function names
- Add proper PHPDoc comments for classes and methods
- Sanitize all user inputs to prevent XSS
- Use prepared statements for database queries
- Implement proper error handling and logging

## Security Guidelines
- Always validate and sanitize user inputs
- Use CSRF tokens for form submissions
- Implement proper authentication and authorization
- Hash passwords using PHP's password_hash()
- Validate file uploads and restrict file types
- Use HTTPS in production
- Log security-related events

## Database Guidelines
- Use the existing database schema in database/schema.sql
- Follow naming conventions (snake_case for tables and columns)
- Create proper indexes for frequently queried columns
- Use foreign key constraints for data integrity
- Implement soft deletes where appropriate

## Integration Notes
- M-Pesa integration uses Safaricom's API (sandbox/production)
- Email service supports SMTP configuration
- SMS service uses HostPinnacle API
- File uploads are stored in storage/uploads/
- Session management with configurable timeout

## Common Patterns
- Controllers extend BaseController
- Models extend BaseModel
- Use the Router class for URL routing
- Flash messages for user feedback
- Helper functions for common operations
- Service classes for external integrations

## Testing and Development
- Test all payment flows in sandbox mode
- Validate email templates before sending
- Check responsive design on mobile devices
- Test user permissions and access controls
- Verify file upload functionality and security

When working with this codebase, prioritize security, user experience, and maintainability. Always test changes thoroughly, especially payment-related functionality.
