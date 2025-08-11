from django.db import models
from django.utils import timezone


# ------------------------------
# Cache Models
# ------------------------------

class Cache(models.Model):
    key = models.CharField(primary_key=True, max_length=255)
    value = models.TextField()
    expiration = models.IntegerField()

    class Meta:
        db_table = 'cache'

    def __str__(self):
        return self.key


class CacheLocks(models.Model):
    key = models.CharField(primary_key=True, max_length=255)
    owner = models.CharField(max_length=255)
    expiration = models.IntegerField()

    class Meta:
        db_table = 'cache_locks'

    def __str__(self):
        return self.key


# ------------------------------
# Core Customer & Fee Models
# ------------------------------

class Customers(models.Model):
    id = models.BigAutoField(primary_key=True)
    name = models.CharField(max_length=255)
    customer_id = models.CharField(max_length=255, blank=True, null=True)
    gender = models.CharField(max_length=45, blank=True, null=True)
    age = models.IntegerField(blank=True, null=True)
    weight = models.CharField(max_length=45, blank=True, null=True)
    height = models.CharField(max_length=45, blank=True, null=True)
    blood_group = models.CharField(max_length=25, blank=True, null=True)
    bp_sugar = models.CharField(max_length=45, blank=True, null=True)
    sugar = models.CharField(max_length=45, blank=True, null=True)
    other_problems = models.CharField(max_length=155, blank=True, null=True)
    date = models.DateField()
    email = models.CharField(unique=True, max_length=255)
    phone = models.CharField(max_length=15)
    address = models.CharField(max_length=255, blank=True, null=True)
    package = models.CharField(max_length=255)
    amount = models.DecimalField(max_digits=10, decimal_places=2, blank=True, null=True)
    advance = models.DecimalField(max_digits=10, decimal_places=2, blank=True, null=True)
    total_amount = models.DecimalField(max_digits=10, decimal_places=2, blank=True, null=True)
    due_date = models.DateField(blank=True, null=True)
    last_paid = models.DateField(blank=True, null=True)
    month = models.CharField(max_length=45, blank=True, null=True)
    photo = models.ImageField(upload_to='customer_photos/', blank=True, null=True)
    transaction_type = models.CharField(max_length=50, default='Cash')
    status = models.IntegerField()
    break_start = models.DateField(blank=True, null=True)
    break_end = models.DateField(blank=True, null=True)
    created_at = models.DateTimeField(blank=True, null=True)
    updated_at = models.DateTimeField(blank=True, null=True)
    message_clicks = models.PositiveIntegerField()

    class Meta:
        db_table = 'customers'

    def __str__(self):
        return f"{self.name} ({self.customer_id})"


class TblAddfees(models.Model):
    id = models.BigAutoField(primary_key=True)
    name = models.CharField(max_length=255)    
    customer = models.ForeignKey(Customers, on_delete=models.CASCADE, related_name='fees')  # ✅ Correct FK
    month = models.CharField(max_length=55)
    join_date = models.DateField(blank=True, null=True)
    date = models.DateField(blank=True, null=True)
    package = models.CharField(max_length=255)
    amount = models.DecimalField(max_digits=10, decimal_places=2)
    due_date = models.DateField(blank=True, null=True)
    status = models.IntegerField()
    transaction_type = models.CharField(max_length=50, default='Cash')
    created_at = models.DateTimeField(blank=True, null=True)
    updated_at = models.DateTimeField(blank=True, null=True)
    last_paid = models.DateField(blank=True, null=True)

    class Meta:
        db_table = 'tbl_addfees'

    def __str__(self):
        return f"{self.name} - {self.package}"


class PaymentHistory(models.Model):
    fee = models.ForeignKey(TblAddfees, on_delete=models.CASCADE, related_name='payment_history')
    date = models.DateField()
    amount = models.DecimalField(max_digits=10, decimal_places=2)
    method = models.CharField(max_length=50, default='Unknown')
    status = models.CharField(max_length=20, default='Completed')
    created_at = models.DateTimeField(default=timezone.now)

    class Meta:
        ordering = ['-date']

    def __str__(self):
        return f"{self.fee.name} - ₹{self.amount} on {self.date}"


# ------------------------------
# Other Tables
# ------------------------------

class TblExpense(models.Model):
    id = models.BigAutoField(primary_key=True)
    date = models.DateField()
    purchase = models.CharField(max_length=255)
    description = models.TextField(blank=True, null=True)
    amount = models.DecimalField(max_digits=10, decimal_places=2)
    status = models.IntegerField()
    created_at = models.DateTimeField(blank=True, null=True)
    updated_at = models.DateTimeField(blank=True, null=True)

    class Meta:
        db_table = 'tbl_expense'

    def __str__(self):
        return self.purchase


class TblUser(models.Model):
    user_id = models.AutoField(primary_key=True)
    name = models.CharField(max_length=155)
    join_date = models.DateTimeField()
    email = models.CharField(max_length=155)
    phone = models.CharField(max_length=45)
    package = models.CharField(max_length=45)
    photo = models.CharField(max_length=255)
    status = models.IntegerField()

    class Meta:
        db_table = 'tbl_user'

    def __str__(self):
        return self.name


class Users(models.Model):
    id = models.BigAutoField(primary_key=True)
    name = models.CharField(max_length=255)
    email = models.CharField(unique=True, max_length=255)
    email_verified_at = models.DateTimeField(blank=True, null=True)
    password = models.CharField(max_length=255)
    remember_token = models.CharField(max_length=100, blank=True, null=True)
    created_at = models.DateTimeField(blank=True, null=True)
    updated_at = models.DateTimeField(blank=True, null=True)

    class Meta:
        db_table = 'users'

    def __str__(self):
        return self.email


class PackageAmounts(models.Model):
    package = models.CharField(max_length=255, unique=True)
    amount = models.DecimalField(max_digits=10, decimal_places=2)
    duration = models.IntegerField(default=1)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'package_amounts'

    def __str__(self):
        return f"{self.package}: ₹{self.amount}"


# ------------------------------
# Background Job Tables
# ------------------------------

class FailedJobs(models.Model):
    id = models.BigAutoField(primary_key=True)
    uuid = models.CharField(unique=True, max_length=255)
    connection = models.TextField()
    queue = models.TextField()
    payload = models.TextField()
    exception = models.TextField()
    failed_at = models.DateTimeField()

    class Meta:
        db_table = 'failed_jobs'

    def __str__(self):
        return self.uuid


class Jobs(models.Model):
    id = models.BigAutoField(primary_key=True)
    queue = models.CharField(max_length=255)
    payload = models.TextField()
    attempts = models.PositiveIntegerField()
    reserved_at = models.PositiveIntegerField(blank=True, null=True)
    available_at = models.PositiveIntegerField()
    created_at = models.PositiveIntegerField()

    class Meta:
        db_table = 'jobs'

    def __str__(self):
        return str(self.id)


class JobBatches(models.Model):
    id = models.CharField(primary_key=True, max_length=255)
    name = models.CharField(max_length=255)
    total_jobs = models.IntegerField()
    pending_jobs = models.IntegerField()
    failed_jobs = models.IntegerField()
    failed_job_ids = models.TextField()
    options = models.TextField(blank=True, null=True)
    cancelled_at = models.IntegerField(blank=True, null=True)
    created_at = models.IntegerField()
    finished_at = models.IntegerField(blank=True, null=True)

    class Meta:
        db_table = 'job_batches'

    def __str__(self):
        return self.name


# ------------------------------
# Auth & Session
# ------------------------------

class PasswordResetTokens(models.Model):
    email = models.CharField(primary_key=True, max_length=255)
    token = models.CharField(max_length=255)
    created_at = models.DateTimeField(blank=True, null=True)

    class Meta:
        db_table = 'password_reset_tokens'

    def __str__(self):
        return self.email


class Sessions(models.Model):
    id = models.CharField(primary_key=True, max_length=255)
    user_id = models.PositiveBigIntegerField(blank=True, null=True)
    ip_address = models.CharField(max_length=45, blank=True, null=True)
    user_agent = models.TextField(blank=True, null=True)
    payload = models.TextField()
    last_activity = models.IntegerField()

    class Meta:
        db_table = 'sessions'

    def __str__(self):
        return self.id


# ------------------------------
# Migration Tracking
# ------------------------------

class Migrations(models.Model):
    migration = models.CharField(max_length=255)
    batch = models.IntegerField()

    class Meta:
        db_table = 'migrations'

    def __str__(self):
        return self.migration
