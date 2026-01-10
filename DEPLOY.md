# Deploying to Railway

Here is the step-by-step guide to deploy your Laravel application to Railway.

## 1. Prepare your Repository
I have already created the necessary `Dockerfile` and `.dockerignore` files for you.
1.  Open your terminal.
2.  Commit these new files:
    ```bash
    git add Dockerfile .dockerignore
    git commit -m "Add Docker deployment configuration"
    git push origin main
    ```

## 2. Create Project in Railway
1.  Go to [Railway.app](https://railway.app/).
2.  Click **"New Project"**.
3.  Select **"Deploy from GitHub repo"**.
4.  Select your repository (`tawreedmed.com` or whatever it is named).
5.  Click **"Deploy Now"**.

## 3. Add Database
1.  In your Railway project view, right-click on the canvas or click **"New"**.
2.  Select **"Database"** -> **"MySQL"**.
3.  Wait for the MySQL service to initialize.

## 4. Configure Environment Variables
1.  Click on your **Laravel Application service** (the one you deployed from GitHub).
2.  Go to the **"Variables"** tab.
3.  Add the following variables (you can get the DB values from the MySQL service **"Variables"** tab):

| Variable | Value |
| :--- | :--- |
| `APP_NAME` | `Tawreed` |
| `APP_ENV` | `production` |
| `APP_KEY` | *(Copy from your local .env or generate a new one)* |
| `APP_DEBUG` | `false` |
| `APP_URL` | *(The https domain Railway gives you)* |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | `${{MySQL.MYSQLHOST}}` |
| `DB_PORT` | `${{MySQL.MYSQLPORT}}` |
| `DB_DATABASE` | `${{MySQL.MYSQLDATABASE}}` |
| `DB_USERNAME` | `${{MySQL.MYSQLUSER}}` |
| `DB_PASSWORD` | `${{MySQL.MYSQLPASSWORD}}` |

*Note: Railway allows you to reference other service variables like `${{MySQL.MYSQLHOST}}` so you don't have to copy-paste hardcoded IPs.*

## 5. Import Database
Since your `database_dump.sql` is included in the project/image, you can import it easily:
1.  Go to your **Laravel Service** in Railway.
2.  Click on the **"Shell"** (or "Exec") tab (Command Line icon).
3.  Run the following command to import your data:
    ```bash
    mysql -h $DB_HOST -u $DB_USERNAME -p$DB_PASSWORD $DB_DATABASE < database_dump.sql
    ```
4.  Run migrations (optional, if you want only structure or updates):
    ```bash
    php artisan migrate
    ```

## 6. Final Steps
1.  Once the import is done, go to the **"Settings"** tab of your Laravel service.
2.  Under **"Networking"**, generate a domain.
3.  Open that domain to see your site!
