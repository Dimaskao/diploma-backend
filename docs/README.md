# WorkWave

## Introduction

This project is a PHP application structured around the Laravel framework, focusing on user profiles, social network interactions, and administrative functionalities.

## Table of Contents

1. [Local Development](#local-development)
2. [About Project](#about-project)
3. [Project Structure](#project-structure)
4. [Key Components](#key-components)
    - [Console Commands](#console-commands)
    - [Enums](#enums)
    - [Events](#events)
    - [Factories](#factories)
    - [Controllers](#controllers)
    - [Requests](#requests)
    - [Resources](#resources)
    - [Interfaces](#interfaces)
    - [Listeners](#listeners)
    - [Models](#models)
    - [Services](#services)
    - [Strategies](#strategies)
5. [Installation](#installation)
6. [Usage](#usage)
7. [Contributing](#contributing)
8. [License](#license)
9. [Stack](#stack)
10. [Team](#team)

## Local Development

```bash
cp .env.example .env
docker compose up -d
docker compose exec fpm sh
> composer install
> php artisan serve
> php artisan schedule:run
```

## About Project

1. **Registration and Authorization:**
    - New user registration capability.
    - Authorization and login to user accounts.
2. **User profile:**
    - Ability to create and edit user profiles, including personal information, photos, education, work experience, and skills.
    - Adding contact information such as email, phone number, etc.
3. **Social Network:**
    - Ability to add and delete contacts.
    - Search and view profiles of other users.
4. **Posts and Vacancies:**
    - Ability to create and publish posts, news, articles.
    - Search and view vacancies, as well as the ability to post own vacancies.
    - Interaction with publications, such as commenting, likes, etc.
5. **Network of Enterprises and Companies:**
    - Creation of company pages.
    - Search and view information about companies, including vacancies, news, contact information.
    - Ability to view user vacancies.
6. **Education and Training:**
    - Ability to add educational institutions, courses, certificates to user profiles.
    - Search and view information about educational institutions, courses, programs.
7. **Messaging and Chat:**
    - Ability to exchange messages between users.
    - Organization of group chats and message broadcasting.
8. **Analytics and Statistics:**
    - Providing users with reports on interaction with their profiles and publications.
    - Collection and analysis of data on user activity on the platform.

## Project Structure

The project is organized as follows:

```plaintext
app/
├── Console/
│   ├── Commands/
│   │   └── UnbanExpiredUsers.php
│   └── Kernel.php
├── Enums/
│   ├── BaseEnum.php
│   ├── Edit.php
│   ├── Method.php
│   ├── Period.php
│   ├── Permission.php
│   ├── ResponseKey.php
│   ├── SearchType.php
│   ├── SubscriptionAction.php
│   ├── UpdateType.php
│   └── UserRole.php
├── Events/
│   ├── MessageSent.php
│   └── UserBanned.php
├── Factories/
│   ├── ProfileStrategyFactory.php
│   └── UserFactory.php
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── Controller.php
│   │   ├── PostController.php
│   │   ├── ProfileController.php
│   │   └── SocialNetworkController.php
│   ├── Requests/
│   │   └── StorePostRequest.php
│   └── Resources/
│       └── PostResource.php
├── Interfaces/
│   ├── Factory.php
│   ├── ProfileStrategy.php
│   └── SpecificProfileService.php
├── Listeners/
│   ├── LogOutBannedUser.php
│   └── MessageSentListener.php
├── Models/
│   ├── Admin.php
│   ├── BannedPost.php
│   ├── BannedUser.php
│   ├── Chat.php
│   ├── ChatUser.php
│   ├── Comment.php
│   ├── Company.php
│   ├── Like.php
│   ├── Message.php
│   ├── Post.php
│   ├── Profile.php
│   ├── SocialNetwork.php
│   ├── Subscription.php
│   └── User.php
├── Services/
│   ├── Profile/
│   │   ├── BaseProfileService.php
│   │   ├── SpecificProfile/
│   │   │   ├── Admin/
│   │   │   │   └── AdminProfileService.php
│   │   │   ├── Company/
│   │   │   │   └── CompanyProfileService.php
│   │   │   └── RegularUser/
│   │   │       ├── RegularUserProfileService.php
│   │   │       └── Handlers/
│   │   │           ├── Delete/
│   │   │           │   └── DeleteHandler.php
│   │   │           ├── Get/
│   │   │           │   ├── GetHandler.php
│   │   │           │   ├── Helpers/
│   │   │           │   │   ├── EducationGetHelper.php
│   │   │           │   │   ├── ProfileGetHelper.php
│   │   │           │   │   ├── SkillsGetHelper.php
│   │   │           │   │   └── WorkExperienceGetHelper.php
│   │   │           └── Update/
│   │   │               ├── UpdateHandler.php
│   │   │               ├── Helpers/
│   │   │               │   ├── ProfileUpdateHelper.php
│   │   │               │   ├── SkillsUpdateHelper.php
│   │   │               │   ├── UserEducationUpdateHelper.php
│   │   │               │   └── WorkExperienceUpdateHelper.php
│   ├── Response/
│   │   └── ResponseService.php
│   └── SocialNetwork/
│       ├── ChatService.php
│       ├── MessageService.php
│       ├── SearchService.php
│       ├── SocialNetworkService.php
│       └── SubscriptionService.php
└── Strategies/
    ├── Profile/
    │   ├── BaseProfileStrategy.php
    │   └── SpecificProfile/
    │       ├── AdminProfileStrategy.php
    │       ├── CompanyProfileStrategy.php
    │       └── RegularUserProfileStrategy.php
```

## Key Components

### Console Commands

- **UnbanExpiredUsers.php**: Command to unban users whose ban period has expired.
- **Kernel.php**: Schedules and registers custom commands.

### Enums

- Enum classes like `BaseEnum`, `Edit`, `Method`, etc., are used for defining various constants throughout the application.

### Events

- **MessageSent.php**: Event triggered when a message is sent.
- **UserBanned.php**: Event triggered when a user is banned.

### Factories

- **ProfileStrategyFactory.php**: Creates instances of profile strategies.
- **UserFactory.php**: Creates instances of users.

### Controllers

- **AuthController.php**: Handles user authentication.
- **Controller.php**: Base controller class.
- **PostController.php**: Manages posts.
- **ProfileController.php**: Manages user profiles.
- **SocialNetworkController.php**: Manages social network interactions.

### Requests

- **StorePostRequest.php**: Validates data for storing a post.

### Resources

- **PostResource.php**: Transforms post data for API responses.

### Interfaces

- Interface classes like `Factory`, `ProfileStrategy`, etc., define the structure for implementing various services.

### Listeners

- **LogOutBannedUser.php**: Listens for the ban event and logs out the banned user.
- **MessageSentListener.php**: Listens for the message sent event.

### Models

- Models like `Admin`, `BannedPost`, `BannedUser`, etc., represent various entities in the application.

### Services

- **Profile**: Handles user profiles.
    - **SpecificProfile**: Handles specific types of profiles (e.g., `Admin`, `Company`, `RegularUser`).
- **ResponseService.php**: Manages API responses.
- **SocialNetwork**: Manages social network functionalities (e.g., `ChatService`, `MessageService`, `SearchService`).

### Strategies

- **Profile**: Defines strategies for handling different profiles.
    - **SpecificProfile**: Specific strategies for different user roles (e.g., `AdminProfileStrategy`, `CompanyProfileStrategy`, `RegularUserProfileStrategy`).

## Installation

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd <repository-directory>
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Set up environment variables:**
   Copy `.env.example` to `.env` and update the configuration values.

4. **Run migrations:**
   ```bash
   php artisan migrate
   ```

5. **Seed the database:**
   ```bash
   php artisan db:seed
   ```

## Usage

- **Start the development server:**
  ```bash
  php artisan serve
  npm run dev
  ```

- **Run tests:**
  ```bash
  php artisan test
  ```

## Contributing

1. **Fork the repository.**
2. **Create a new branch:**
   ```bash
   git checkout -b feature-branch
   ```

3. **Make your changes and commit them:**
   ```bash
   git commit -m "Description of changes"
   ```

4. **Push to the branch:**
   ```bash
   git push origin feature-branch
   ```

5. **Submit a pull request.**

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Stack

- **Frontend:** React, Redux + Toolkit, RTK-Query, Tailwind, MUI, JWT, *Websocket(Socket.io), *I18n, *Dark Mode
- **Backend:** PHP, Laravel, PostgreSQL/My - **DevOps:** Docker, Kubernetes, ElasticSearch, Jenkins, JEST
- **Mobile:** React Native

## Team

- **Frontend:** Kirill, Alexander, Vladimir
- **Backend:** Dmitry, Victoria, Vladimir
- **Design:** Margaret, Angela
