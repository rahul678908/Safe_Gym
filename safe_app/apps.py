from django.apps import AppConfig


class SafeAppConfig(AppConfig):
    default_auto_field = 'django.db.models.BigAutoField'
    name = 'safe_app'
