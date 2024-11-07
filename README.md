# Volunteer Matching System (VMS)




https://github.com/user-attachments/assets/fb58b2c7-c724-4564-b84f-0787404e783f




## Introduction

The **Volunteer Matching System (VMS)** is a web-based application developed to streamline the interaction between volunteers and organizations. VMS simplifies the process of connecting willing volunteers with meaningful opportunities posted by companies and organizations. Volunteers can browse opportunities and apply for positions, while organizations can post and manage applications, bridging the gap between social needs and community involvement.

### Problem Statement

The traditional process of volunteer matching is often manual and time-consuming, leading to missed opportunities and inefficient connections.

### Solution

The VMS automates volunteer recruitment and placement, using an advanced algorithm to match volunteers with opportunities based on their skills, interests, and availability.

## How It Works

Built using a client-server architecture, VMS utilizes structured data transfer via XML and integrates a MySQL database for persistent storage. The system offers a seamless user experience enhanced by JavaScript scripts and an API for location-based services.

### Key Features

1. **Registration**: Individuals and organizations create accounts on the platform.
2. **Profile Creation**: Users can build detailed profiles with information on interests, skills, and availability.
3. **Opportunity Posting**: Organizations post volunteer opportunities with specifics like role type, time commitment, and skills required.
4. **Opportunity recomendation**: Using your profile data, location, and availability, the platform ranks and displays volunteer opportunities, prioritizing those that best match your interests and capabilities.
5. **Opportunity Application**:
6. **Map View**:


## Benefits

- **Customized Matches**: Tailored matches based on preferences and skills.
- **Enhanced Volunteer Satisfaction**: Volunteers find roles suited to their interests.
- **Increased Organizational Impact**: Efficient recruitment and retention of volunteers improve an organization’s impact.


---

## System Requirements

- **Frontend**: HTML5, CSS3, JavaScript (AJAX, XMLHttpRequest)
- **Backend**: PHP, MySQL (using PDO for secure database access)
- **Data Transfer**: XML
- **API**: Google Maps API for location-based searches

---

## Architecture Overview

### Technology Stack

#### Client-Side
- **HTML5 & CSS3**: For building the user interface, including forms and dashboards.
- **JavaScript**: Manages asynchronous communication and dynamic interface updates.
- **XML**: Used for structured data transfer between the client and server.

#### Server-Side
- **PHP**: Handles server-side logic, including form processing, session management, and database interaction.
- **MySQL**: Manages persistent data for user accounts, opportunities, and applications.
- **PDO (PHP Data Objects)**: Ensures secure database interactions with prepared statements to prevent SQL injection.

#### API
- **Google Maps API**: Provides map-based search functionality, helping volunteers locate opportunities.

### Core Components

1. **Client-Server Interaction**
   - The client communicates with the server via AJAX, using XML data transfer for a smooth, asynchronous experience.
   - PHP scripts on the server process requests, fetch data from the database, and respond with XML data.

2. **Data Transfer Using XML**
   - XML is the primary format for structured data exchange, allowing organized and easily parsed data transfer between client and server.

3. **Database Integration**
   - VMS integrates with a MySQL database with a structured schema:
      - **`users`**: Stores user data, including name, email, and role (volunteer or company).
      - **`volunteer_postings`**: Stores information about opportunities posted by companies.
      - **`applications`**: Maps volunteer applications to specific opportunities.

4. **API Integration**
   - Google Maps API allows volunteers to view opportunity locations, with a "View Map" feature displaying coordinates on a map.

5. **Client-Side Scripting**
   - JavaScript handles various functions:
      - **AJAX Requests**: Sends and receives data from the server to update the interface.
      - **Form Validation**: Ensures correct data entry before form submission.
      - **XML Parsing**: Processes XML data for dynamic updates on the dashboard.

6. **Optional Socket Programming**
   - WebSockets could enhance VMS by allowing real-time updates, providing live notifications for new opportunities or application statuses.

---

### **Core Files and Scripts**

1. **`auth_middleware.php`**
   - **Purpose**: This middleware checks whether a user is authenticated (logged in) before allowing access to protected pages.
   - **Connection**: Included in pages where authentication is required, such as `post_opportunity.php`, `fetch_applications.php`, `delete_opportunity.php`, and `edit_opportunity.php`. If the user is not authenticated, they are redirected to the login page (`login.html`).

2. **`db.php`**
   - **Purpose**: Establishes a database connection using PDO.
   - **Connection**: Included in nearly all scripts that require database access to perform actions such as fetching, updating, or deleting records.

---

### **Page and Script Documentation**

#### **1. User Authentication**
   - **Pages**: `login.html`, `auth_middleware.php`
   - **Purpose**: To ensure users are authenticated before accessing the platform.
   - **Functionality**:
      - `login.html`: Provides a form for users to enter their credentials.
      - `auth_middleware.php`: Checks for a valid session on protected pages. If a session does not exist, it redirects the user to `login.html`.

#### **2. Dashboard (e.g., `dashboard.php`)**
   - **Purpose**: Serves as the central hub for users after they log in, displaying relevant information based on the user’s role (`volunteer` or `company`).
   - **Connection**:
      - Uses scripts such as `fetch_applications.php` and `fetch_volunteer_profile.php` to dynamically load data based on the user's role.
      - Links to other actions (e.g., posting or applying to opportunities, viewing profiles).

#### **3. Opportunity Posting and Management**

   - **Script**: `post_opportunity.php`
      - **Purpose**: Allows companies to post new volunteer opportunities.
      - **Functionality**:
         - Authenticated companies enter details like position, date, location, and description, which are saved in the `volunteer_postings` table.
         - Connection: Uses `auth_middleware.php` for access control and `db.php` to save opportunity details.

   - **Script**: `edit_opportunity.php`
      - **Purpose**: Allows companies to edit details of previously posted opportunities.
      - **Functionality**:
         - Retrieves current details of the opportunity and provides an editable form for the company.
         - Upon submission, updates the `volunteer_postings` table with the new details.
      - **Connection**: Uses `auth_middleware.php` for authentication, `db.php` for database connection, and links back to the dashboard.

   - **Script**: `delete_opportunity.php`
      - **Purpose**: Deletes an opportunity posted by the company.
      - **Functionality**:
         - Requires the `opportunity_id` from a POST request.
         - Deletes the specified opportunity from the `volunteer_postings` table.
      - **Connection**: Authenticated via `auth_middleware.php`, uses `db.php` for database access, and redirects to `dashboard.php`.

---

#### **4. Volunteer Application Management**

   - **Script**: `apply_opportunity.php`
      - **Purpose**: Allows volunteers to apply for a specific opportunity.
      - **Functionality**:
         - Adds a new entry in the `applications` table linking the volunteer to the chosen opportunity.
         - Verifies that the volunteer is authenticated and has a valid session.
      - **Connection**: Linked from opportunity listings on the volunteer's dashboard.

   - **Script**: `fetch_applications.php`
      - **Purpose**: Fetches all applications submitted for a company’s posted opportunities.
      - **Functionality**:
         - Retrieves applications, including volunteer name, email, and the applied position, and returns this data as XML.
      - **Connection**: 
         - Called on the company’s dashboard to display applications.
         - Uses `auth_middleware.php` for access control and `db.php` for fetching data.
         - Works with `fetch_volunteer_profile.php` to view more details on individual volunteers.

---

#### **5. Volunteer Profile**

   - **Script**: `fetch_volunteer_profile.php`
      - **Purpose**: Fetches and displays a volunteer’s profile, including skills, location, interests, and availability.
      - **Functionality**:
         - Retrieves volunteer data based on the `id` parameter, ensuring that only authenticated users can access this information.
      - **Connection**:
         - Accessed via a link in `fetch_applications.php` so companies can view applicant profiles.
         - Uses `db.php` to fetch volunteer data from the `users` table.

---

### **How Scripts Work Together**

1. **User Flow for Volunteers**:
   - Volunteers log in via `login.html`.
   - After logging in, they’re directed to `dashboard.php`, where they can see open opportunities.
   - They can apply for an opportunity using `apply_opportunity.php`, which adds an application entry in the database.
   - When companies review applications via `fetch_applications.php`, they can view volunteer profiles by linking to `fetch_volunteer_profile.php`.

2. **User Flow for Companies**:
   - Companies also log in via `login.html`.
   - After login, `dashboard.php` presents options for posting new opportunities (`post_opportunity.php`).
   - Companies can review volunteer applications for their postings through `fetch_applications.php`, with links to view each volunteer’s profile (`fetch_volunteer_profile.php`).

3. **Data Flow and Interactions**:
   - **Session Management**: `auth_middleware.php` checks each session for a valid `user_id`. This middleware is critical for ensuring that sensitive actions like posting or viewing applications are restricted to authorized users.
   - **XML Data Exchange**: `fetch_applications.php` provides XML-formatted data to dynamically display applications on the company dashboard. JavaScript can parse this XML and populate HTML elements on the page. This method allows for smooth, client-side updating without full-page reloads.
   - **Dynamic Volunteer Profile Access**: When companies view applications, they can access volunteer profiles through `fetch_volunteer_profile.php`. This script retrieves profile data in HTML format, allowing companies to see more detailed information about each volunteer, such as skills and availability.
   - **Error Handling and Redirection**: Most scripts include error handling, redirecting the user back to the dashboard with success or error messages appended as query parameters (e.g., `dashboard.php?success=applied`). This allows users to understand the outcome of their actions and proceed accordingly.

## Database Schema

### Tables and Columns

1. **`users`**  
   Stores information about all registered users on the platform, including their profile details, contact preferences, and role-specific data.

   - `id`: Primary key, unique identifier for each user.
   - `name`: User's full name.
   - `email`: Unique email address.
   - `password`: User's hashed password.
   - `role`: User's role, either `volunteer` or `company`.
   - `created_at`: Timestamp of when the user account was created.
   - `location`: User's current location or city.
   - `skills`: List of the user's skills, stored as comma-separated values or a JSON array.
   - `interests`: List of the user's interests, stored as comma-separated values or a JSON array.
   - `availability`: User's availability for volunteer opportunities. Options include `weekdays`, `weekends`, `evenings`, or `anytime`.
   - `preferred_locations`: List of locations preferred by the user for volunteering, stored as a comma-separated string.
   - `receive_email_updates`: Boolean indicating if the user wants to receive email updates. Defaults to `TRUE`.
   - `receive_sms_updates`: Boolean indicating if the user wants to receive SMS updates. Defaults to `FALSE`.
   - `subscribe_newsletter`: Boolean indicating if the user is subscribed to the newsletter. Defaults to `TRUE`.
   - `profile_picture_url`: URL to the user's profile picture.
   - `updated_at`: Timestamp that records the last time the user's profile was updated. Updates automatically whenever profile information changes. 

2. **`volunteer_postings`**
   - `id`: Primary key, unique identifier for each posting.
   - `company_id`: Foreign key linking to the company in `users`.
   - `position`: Title of the volunteer position.
   - `date`: Scheduled date for the volunteer opportunity.
   - `location`: Location of the opportunity.
   - `description`: Details about the role of the volunteer.
   - `created_at`: Timestamp of posting creation.

3. **`applications`**
   - `id`: Primary key, unique identifier for each application.
   - `volunteer_id`: Foreign key linking to the volunteer in `users`.
   - `posting_id`: Foreign key linking to a posting in `volunteer_postings`.
   - `application_date`: Timestamp of application submission.
