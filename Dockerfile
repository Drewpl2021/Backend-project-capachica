#########################################
# APP CONFIG
#########################################
APP_ENV=production
APP_KEY= # 👈 pon aquí el valor generado con php artisan key:generate
APP_DEBUG=false
APP_URL=https://backend-capachica.onrender.com/ # 👈 pon la URL real de tu backend en Render

#########################################
# LOGGING
#########################################
LOG_CHANNEL=stack
LOG_LEVEL=info

#########################################
# DATABASE → Railway values
#########################################
DB_CONNECTION=mysql
DB_HOST=mysql.railway.internal                # 👈 MYSQLHOST
DB_PORT=3306                                  # 👈 MYSQLPORT
DB_DATABASE=railway                           # 👈 MYSQLDATABASE
DB_USERNAME=root                              # 👈 MYSQLUSER
DB_PASSWORD=bXcsLVDBtIZzbsRXZDiVtEWFNkvtetMVb # 👈 MYSQLPASSWORD

#########################################
# CACHE & QUEUE
#########################################
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

#########################################
# MAIL → dejar así para pruebas
#########################################
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

#########################################
# AWS → deja vacío si no usas S3
#########################################
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false
