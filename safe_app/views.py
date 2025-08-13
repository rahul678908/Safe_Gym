from django.shortcuts import render, redirect
from django.contrib.auth import authenticate, login, logout
from django.contrib import messages
from django.contrib.auth.decorators import login_required
from django.views.decorators.cache import never_cache
from .models import Customers, PaymentHistory
from django.shortcuts import render, redirect, get_object_or_404
from django.http import JsonResponse
from django.contrib import messages
from django.views.decorators.csrf import csrf_exempt
from django.utils.decorators import method_decorator
from django.core.paginator import Paginator
from django.db.models import Q
import json
from .models import TblExpense, TblUser, PackageAmounts
from django.shortcuts import render, get_object_or_404
from django.http import JsonResponse
from django.core.files.storage import default_storage
from django.core.files.base import ContentFile
import os
from django.http import HttpResponse
from reportlab.pdfgen import canvas
from reportlab.lib.pagesizes import A4
from .models import TblAddfees
from django.db.models.functions import TruncMonth  # Import TruncMonth
from django.db.models import Sum
from django.shortcuts import render
from django.http import JsonResponse
from django.db.models import Sum, Count
from django.db.models.functions import TruncMonth
from .models import TblAddfees, Customers
from datetime import datetime, timedelta
from django.utils import timezone
import json
import logging
from datetime import datetime, date, timedelta
import urllib.parse
from django.http import JsonResponse
from django.shortcuts import get_object_or_404
import logging

logger = logging.getLogger(__name__)

# gym_app/views.py
from django.http import JsonResponse
from django.shortcuts import get_object_or_404
from django.utils import timezone
from django.contrib.auth.decorators import login_required
import logging

logger = logging.getLogger(__name__)

# gym_app/views.py
from django.http import JsonResponse
from django.shortcuts import get_object_or_404
from django.utils import timezone
from django.contrib.auth.decorators import login_required
import logging
import urllib.parse
import json

logger = logging.getLogger(__name__)

# gym_app/views.py
from django.http import JsonResponse
from django.shortcuts import get_object_or_404
from django.utils import timezone
from django.contrib.auth.decorators import login_required
import logging
import urllib.parse
import json



logger = logging.getLogger(__name__)



from datetime import datetime
from django.utils.dateparse import parse_datetime, parse_date

def safe_iso(value):
    if isinstance(value, str):
        try:
            value = parse_datetime(value) or parse_date(value)
        except Exception:
            return ''
    return value.isoformat() if value else ''



@never_cache
def admin_login(request):
    if request.user.is_authenticated:
        return redirect('dashboard')
    
    if request.method == 'POST':
        email = request.POST.get('email')
        password = request.POST.get('password')
        user = authenticate(request, username=email, password=password)
        
        if user is not None:
            login(request, user)
            request.session.set_expiry(0)  # Session expires on browser close
            return redirect('dashboard')
        else:
            messages.error(request, 'Invalid email or password')
            return render(request, 'safe_admin/login.html')
    
    return render(request, 'safe_admin/login.html')

from datetime import date  # Import date directly
from dateutil.relativedelta import relativedelta

@login_required
@never_cache
def dashboard(request):
    if not request.user.is_authenticated:
        return redirect('admin_login')

    # Calculate current month range
    today = date.today()  # Use date.today() instead of datetime.today()
    first_day = today.replace(day=1)
    next_month = first_day + relativedelta(months=1)

    # Dynamic data
    monthly_revenue = TblAddfees.objects.filter(
        date__gte=first_day,
        date__lt=next_month
    ).aggregate(total=Sum('amount'))['total'] or 0.00

    active_trainers = TblUser.objects.filter(status=1).count()  # Assuming status=1 means active
    pending_fees = TblAddfees.objects.filter(status=0).count()  # Assuming status=0 means pending

    context = {
        'total_members': Customers.objects.count(),
        'monthly_revenue': monthly_revenue,
        'active_trainers': active_trainers,
        'pending_fees': pending_fees,
        'revenue_change': 10,  # Replace with actual calculation
        'trainer_status': 'No change',  # Replace with actual logic
        'new_members': Customers.objects.filter(date__gte=first_day).count(),
        'overdue_fees': TblAddfees.objects.filter(due_date__lt=today, status=0).count(),
        'total_income': TblAddfees.objects.aggregate(total=Sum('amount'))['total'] or 0.00,
        'total_expenses': TblExpense.objects.aggregate(total=Sum('amount'))['total'] or 0.00,
        'net_profit': (TblAddfees.objects.aggregate(total=Sum('amount'))['total'] or 0.00) -
                      (TblExpense.objects.aggregate(total=Sum('amount'))['total'] or 0.00),
    }
    return render(request, 'safe_admin/dashboard.html', context)

from django.contrib.auth.decorators import login_required
from django.http import JsonResponse
from django.shortcuts import render
from django.utils import timezone
from datetime import datetime, timedelta
import logging
from .models import Customers, PackageAmounts, TblAddfees

logger = logging.getLogger(__name__)

@login_required(login_url='admin_login')
def add_user(request):
    """Add a new customer and corresponding fee details to Customers and TblAddfees."""
    if request.method == 'POST':
        try:
            # Get form data
            name = request.POST.get('name')
            customer_id = request.POST.get('customer_id')
            gender = request.POST.get('gender')
            age = request.POST.get('age')
            email = request.POST.get('email')
            phone = request.POST.get('phone')
            address = request.POST.get('address')
            weight = request.POST.get('weight')
            height = request.POST.get('height')
            blood_group = request.POST.get('blood_group')
            bp_sugar = request.POST.get('bp_sugar')
            sugar = request.POST.get('sugar')
            other_problems = request.POST.get('other_problems')
            package = request.POST.get('package')
            transaction_type = request.POST.get('transaction_type')  # New field
            advance = request.POST.get('advance', 0) or 0
            date = request.POST.get('date')  # join date
            month = request.POST.get('month')
            status = request.POST.get('status', '1')
            message_clicks = request.POST.get('message_clicks', '0')
            photo = request.FILES.get('photo')

            # Validate required fields
            required_fields = {
                'name': name,
                'email': email,
                'phone': phone,
                'package': package,
                'date': date,
                'transaction_type': transaction_type  # Add transaction_type to required fields
            }
            for field_name, field_value in required_fields.items():
                if not field_value or field_value.strip() == '':
                    return JsonResponse({'success': False, 'message': f'{field_name.capitalize()} is required.'}, status=400)

            # Validate transaction_type
            valid_transaction_types = ['Cash', 'Credit Card', 'UPI', 'Bank Transfer']
            if transaction_type not in valid_transaction_types:
                return JsonResponse({'success': False, 'message': 'Invalid transaction type.'}, status=400)

            # Get package amount and duration
            try:
                package_obj = PackageAmounts.objects.get(package=package)
                amount = float(package_obj.amount)
                duration_months = int(package_obj.duration) if package_obj.duration else 1
            except PackageAmounts.DoesNotExist:
                return JsonResponse({'success': False, 'message': f'Package "{package}" not found.'}, status=400)

            # Calculate due date automatically
            try:
                start_date = datetime.strptime(date, '%Y-%m-%d').date()
                due_date = start_date + timedelta(days=30 * duration_months)
            except ValueError:
                return JsonResponse({'success': False, 'message': 'Invalid date format for start date.'}, status=400)

            # Calculate total amount
            try:
                
                advance_amount = float(advance)
            except (ValueError, TypeError):
                advance_amount = 0
            total_amount = amount - advance_amount

            # Create customer
            customer = Customers(
                name=name,
                customer_id=customer_id or None,
                gender=gender or None,
                age=int(age) if age and age.isdigit() else None,
                email=email,
                phone=phone,
                address=address,
                weight=weight,
                height=height,
                blood_group=blood_group,
                bp_sugar=bp_sugar,
                sugar=sugar,
                other_problems=other_problems,
                package=package,
                transaction_type=transaction_type,  # Save transaction_type
                amount=amount,
                advance=advance_amount,
                total_amount=total_amount,
                date=start_date,
                due_date=due_date,
                month=month,
                status=int(status),
                message_clicks=int(message_clicks),
                created_at=timezone.now(),
                updated_at=timezone.now(),
                photo=photo or None
            )
            customer.save()

            print(f"Customer created: {customer.name} (ID: {customer.id})")
            # Save fee entry
            # Save fee entry
            try:
                fee_record = TblAddfees.objects.create(
                    name=customer.name,
                    customer=customer,
                    month=month or '',
                    join_date=start_date,
                    date=start_date,
                    package=package,
                    amount=amount,
                    due_date=due_date,
                    status=int(status),
                    transaction_type=transaction_type,
                    created_at=timezone.now(),
                    updated_at=timezone.now(),
                    last_paid=start_date if advance_amount > 0 else None
                )
                print(f"Fee details saved for customer: {customer.name} (ID: {customer.id})")

                # ✅ Save initial payment history entry
                PaymentHistory.objects.create(
                    fee=fee_record,
                    date=start_date,
                    amount=amount,  
                    method=transaction_type,
                    status='Completed',
                    created_at=timezone.now()
                )
                print(f"Payment history created for fee ID: {fee_record.id}")

            except Exception:
                logger.error("Error saving fee or payment history", exc_info=True)
                return JsonResponse({'success': False, 'message': 'Error saving fee details or payment history.'}, status=500)

            return JsonResponse({'success': True, 'message': 'Customer added successfully!', 'customer_id': customer.id})

        except Exception as e:
            logger.error(f"Error adding customer: {str(e)}", exc_info=True)
            return JsonResponse({'success': False, 'message': f'Error adding customer: {str(e)}'}, status=500)

    # GET request
    context = {
        'total_customers': Customers.objects.count()
    }
    return render(request, 'safe_admin/add_user.html', context)

import re
from dateutil.relativedelta import relativedelta
from datetime import datetime

def calculate_due_date(join_date, duration):
    """Calculate due date based on join date and package duration."""
    try:
        duration_num = int(duration.split()[0]) if duration else 1
        due_date = join_date + timezone.timedelta(days=duration_num * 30)
        return due_date
    except (ValueError, AttributeError) as e:
        logger.error(f"Error calculating due date: {str(e)}")
        return join_date + timezone.timedelta(days=30)
                        
from django.utils.dateformat import DateFormat

@login_required(login_url='admin_login')
@never_cache
def fee_details(request):
    try:
        # Get current month and year
        current_date = datetime.now()
        current_month = current_date.strftime('%B %Y')  # e.g., "July 2025"

        # Aggregated statistics
        total_collection = Customers.objects.aggregate(
            total=Sum('amount', filter=Q(status=1))
        )['total'] or 0.0

        this_month_collection = Customers.objects.filter(
            month=current_month, status=1
        ).aggregate(
            total=Sum('amount')
        )['total'] or 0.0

        pending_dues = Customers.objects.filter(
            status=2
        ).aggregate(
            total=Sum('amount')
        )['total'] or 0.0

        active_members = Customers.objects.filter(status=1).count()

        # Fetch customer-wise fee-related data (optional: only active members)
        customer_fees = Customers.objects.filter(status=1).values(
            'name',
            'customer_id',
            'amount',
            'advance',
            'total_amount',
            'last_paid',
            'month',
            'due_date'
        )

        # Format date fields for template
        formatted_customers = []
        for c in customer_fees:
            formatted_customers.append({
                'name': c['name'],
                'customer_id': c['customer_id'],
                'amount': c['amount'],
                'advance': c['advance'],
                'total_amount': c['total_amount'],
                'last_paid': c['last_paid'].strftime('%Y-%m-%d') if c['last_paid'] else '',
                'month': c['month'],
                'due_date': c['due_date'].strftime('%Y-%m-%d') if c['due_date'] else '',
            })

        context = {
            'total_collection': total_collection,
            'this_month_collection': this_month_collection,
            'pending_dues': pending_dues,
            'active_members': active_members,
            'customer_fees': formatted_customers,  # Pass to template
        }

        return render(request, 'safe_admin/fee_details.html', context)

    except Exception as e:
        logger.error(f"Error in fee_details view: {str(e)}", exc_info=True)
        context = {
            'total_collection': 0.0,
            'this_month_collection': 0.0,
            'pending_dues': 0.0,
            'active_members': 0,
            'error_message': f'Error loading fee details: {str(e)}'
        }
        return render(request, 'safe_admin/fee_details.html', context)

    
from datetime import datetime, date, timedelta
from django.utils import timezone
from django.db.models import Sum
from django.contrib.auth.decorators import login_required
from django.views.decorators.cache import never_cache
from django.shortcuts import render
from safe_app.models import TblAddfees, Customers

@login_required(login_url='admin_login')
@never_cache
def income_report(request):
    today = timezone.now().date()
    current_year = today.year
    current_month = today.month

    # Generate available months
    months = [
        {'num': str(i).zfill(2), 'name': date(2000, i, 1).strftime('%B')}
        for i in range(1, 13)
    ]
    available_months = [
        (current_year, months),
        (current_year - 1, months)
    ]

    # Total income
    total_income = TblAddfees.objects.aggregate(total=Sum('amount'))['total'] or 0

    # Date range handling - alternative approach
    def get_date_range(start_date, end_date):
        return (start_date, end_date)

    # Current month range
    first_day_of_month = today.replace(day=1)
    month_start, month_end = get_date_range(first_day_of_month, today)

    # This month's income - using date range instead of timestamp
    this_month_income = TblAddfees.objects.filter(
        date__gte=month_start,
        date__lte=month_end
    ).aggregate(total=Sum('amount'))['total'] or 0

    # Last month range
    last_month_end = first_day_of_month - timedelta(days=1)
    last_month_start = last_month_end.replace(day=1)
    last_month_start, last_month_end = get_date_range(last_month_start, last_month_end)

    # Last month's income
    last_month_income = TblAddfees.objects.filter(
        date__gte=last_month_start,
        date__lte=last_month_end
    ).aggregate(total=Sum('amount'))['total'] or 0

    # Calculate income change
    income_change = ((this_month_income - last_month_income) / last_month_income * 100) if last_month_income else 100

    # Yearly income data
    year_start = date(current_year, 1, 1)
    year_end = date(current_year, 12, 31)
    year_start, year_end = get_date_range(year_start, year_end)

    monthly_incomes = {}
    income_records = TblAddfees.objects.filter(
        date__gte=year_start,
        date__lte=year_end
    ).values_list('date', 'amount')

    for record_date, amount in income_records:
        try:
            if isinstance(record_date, (int, float)):
                # Handle timestamp case
                record_date = datetime.fromtimestamp(record_date).date()
            elif isinstance(record_date, str):
                # Handle string date case
                record_date = datetime.strptime(record_date, '%Y-%m-%d').date()
            
            month_key = record_date.strftime('%Y-%m')
            monthly_incomes[month_key] = monthly_incomes.get(month_key, 0) + float(amount)
        except (ValueError, TypeError, AttributeError):
            continue

    # Prepare chart data
    sorted_months = sorted(monthly_incomes.items())
    chart_data = {
        'labels': [datetime.strptime(month, '%Y-%m').strftime('%b %Y') for month, _ in sorted_months],
        'values': [amount for _, amount in sorted_months]
    }

    # Calculate averages
    avg_monthly_income = sum(monthly_incomes.values()) / len(monthly_incomes) if monthly_incomes else 0

    # Last year comparison
    last_year_start = date(current_year - 1, 1, 1)
    last_year_end = date(current_year - 1, 12, 31)
    last_year_start, last_year_end = get_date_range(last_year_start, last_year_end)
    
    last_year_income = TblAddfees.objects.filter(
        date__gte=last_year_start,
        date__lte=last_year_end
    ).aggregate(total=Sum('amount'))['total'] or 0

    avg_last_year = last_year_income / 12 if last_year_income else 0
    avg_monthly_change = ((avg_monthly_income - avg_last_year) / avg_last_year * 100) if avg_last_year else 100

    # Member statistics
    active_members = Customers.objects.filter(status=1).count()
    new_members = Customers.objects.filter(
        date__gte=month_start,
        date__lte=month_end,
        status=1
    ).count()

    # Package data
    packages = TblAddfees.objects.values_list('package', flat=True).distinct()

    context = {
        'total_income': float(total_income),
        'this_month_income': float(this_month_income),
        'avg_monthly_income': float(avg_monthly_income),
        'income_change': income_change,
        'avg_monthly_change': avg_monthly_change,
        'active_members': active_members,
        'new_members': new_members,
        'packages': packages,
        'available_months': available_months,
        'current_year': current_year,
        'current_month': str(current_month).zfill(2),
        'chart_data': chart_data
    }
    
    return render(request, 'safe_admin/income_report.html', context)

@login_required
@never_cache
def expense_report(request):
    total_expenses = TblExpense.objects.aggregate(total=Sum('amount'))['total'] or 0
    today = timezone.now().date()
    first_day_of_month = today.replace(day=1)

    this_month_expenses = TblExpense.objects.filter(
        date__gte=first_day_of_month,
        date__lte=today
    ).aggregate(total=Sum('amount'))['total'] or 0

    last_month_end = first_day_of_month - timedelta(days=1)
    last_month_start = last_month_end.replace(day=1)
    last_month_expenses = TblExpense.objects.filter(
        date__gte=last_month_start,
        date__lte=last_month_end
    ).aggregate(total=Sum('amount'))['total'] or 0

    expense_change = ((this_month_expenses - last_month_expenses) / last_month_expenses * 100) if last_month_expenses else 100
    this_month_change = expense_change

    current_year = today.year
    monthly_expenses = TblExpense.objects.filter(
        date__year=current_year
    ).annotate(
        expense_month=TruncMonth('date')  # Renamed to avoid conflicts
    ).values('expense_month').annotate(total=Sum('amount')).order_by('expense_month')
    avg_monthly_expenses = sum(item['total'] for item in monthly_expenses) / (len(monthly_expenses) or 1)

    last_year_expenses = TblExpense.objects.filter(
        date__year=current_year - 1
    ).aggregate(total=Sum('amount'))['total'] or 0
    avg_monthly_change = ((avg_monthly_expenses - (last_year_expenses / 12)) / (last_year_expenses / 12) * 100) if last_year_expenses else 100

    fixed_expenses = TblExpense.objects.filter(
        purchase__in=['Rent', 'Utilities']
    ).aggregate(total=Sum('amount'))['total'] or 0

    expense_categories = TblExpense.objects.values('purchase').distinct().values_list('purchase', flat=True)

    context = {
        'total_expenses': total_expenses,
        'this_month_expenses': this_month_expenses,
        'avg_monthly_expenses': avg_monthly_expenses,
        'fixed_expenses': fixed_expenses,
        'expense_change': expense_change,
        'this_month_change': this_month_change,
        'avg_monthly_change': avg_monthly_change,
        'expense_categories': expense_categories,
    }
    return render(request, 'safe_admin/expense_report.html', context)

@login_required
@never_cache
def expenses(request):
    if not request.user.is_authenticated:
        return redirect('admin_login')
    context = {'expenses': []}
    return render(request, 'safe_admin/expenses.html', context)

@login_required(login_url='admin_login')
@never_cache
def fee_adding(request):
    """Display the fee adding page with all fee records and handle fee addition."""
    if request.method == 'POST':
        try:
            name = request.POST.get('name')
            customerid = request.POST.get('customerid')
            month = request.POST.get('month')
            join_date = request.POST.get('join_date')
            date = request.POST.get('date')
            package = request.POST.get('package')
            due_date = request.POST.get('due_date')
            status = request.POST.get('status', '1')

            # Validate required fields
            required_fields = {'name': name, 'customerid': customerid, 'month': month, 'package': package, 'date': date}
            for field_name, field_value in required_fields.items():
                if not field_value or field_value.strip() == '':
                    return JsonResponse({
                        'success': False,
                        'message': f'{field_name.capitalize()} is required.'
                    }, status=400)

            package_amount = PackageAmounts.objects.filter(package=package).first()
            if not package_amount:
                return JsonResponse({
                    'success': False,
                    'message': 'Invalid package selected.'
                }, status=400)
            amount = float(package_amount.amount)

            fee = TblAddfees(
                name=name,
                customerid=customerid,
                month=month,
                join_date=join_date if join_date else None,
                date=date,
                package=package,
                amount=amount,
                due_date=due_date if due_date else None,
                status=int(status),
                created_at=timezone.now(),
                updated_at=timezone.now()
            )
            fee.save()
            return JsonResponse({'success': True, 'message': 'Fee added successfully'})
        except Exception as e:
            logger.error(f"Error adding fee: {str(e)}", exc_info=True)
            return JsonResponse({'success': False, 'message': f'Error adding fee: {str(e)}'}, status=500)

    # For GET request, render the fee_adding template
    fees = TblAddfees.objects.all().order_by('-created_at')
    context = {
        'fees': fees,
        'total_records': fees.count(),
        'packages': PackageAmounts.objects.all()
    }
    return render(request, 'safe_admin/fee_adding.html', context)

@login_required
@never_cache
def admin_logout(request):
    logout(request)
    request.session.flush()
    response = redirect('admin_login')
    response['Cache-Control'] = 'no-cache, no-store, must-revalidate'
    response['Pragma'] = 'no-cache'
    response['Expires'] = '0'
    return response

@never_cache
def customer_list(request):
    customers = Customers.objects.all()
    return render(request, 'test.html', {'customers': customers})

def home(request):
    return render(request, 'safe_user/index.html')

def about(request):
    return render(request, 'safe_user/about.html')

def services(request):
    return render(request, 'safe_user/services.html')

def schedule(request):
    return render(request, 'safe_user/schedule.html')

def gallery(request):
    return render(request, 'safe_user/gallery.html')

def blog(request):
    return render(request, 'safe_user/blog.html')

def blog_detail(request, post_id):
    return render(request, 'safe_user/blog_detail.html', {'post_id': post_id})

def blog_details(request):
    return render(request, 'safe_user/blog_details.html')

def elements(request):
    return render(request, 'safe_user/elements.html')

def contact(request):
    return render(request, 'safe_user/contact.html')

def form(request):
    return render(request, 'safe_user/form.html')

#============================Add User========================#
# views.py


# Set up logging
logger = logging.getLogger(__name__)

# Package duration mapping (in months)
PACKAGE_DURATIONS = {
    'Monthly': 1,
    'Quarterly': 3,
    'Yearly': 12
}

def get_customers_data(request):
    customers = Customers.objects.all()
    data = []
    for c in customers:
        fee = TblAddfees.objects.filter(customer=c).first()
        data.append({
            'id': c.id,
            'name': c.name,
            'customer_id': c.customer_id,
            'gender': c.gender,
            'age': c.age,
            'email': c.email,
            'phone': c.phone,
            'package': fee.package if fee else c.package,
            'transaction_type': fee.transaction_type if fee else c.transaction_type,
            'amount': float(fee.amount) if fee and fee.amount is not None else float(c.amount) if c.amount is not None else None,
            'status': fee.status if fee else c.status,
            'created_at': safe_iso(c.created_at),
            'updated_at': safe_iso(c.updated_at),
            'due_date': safe_iso(fee.due_date if fee else c.due_date),
            'last_paid': safe_iso(fee.last_paid if fee else c.last_paid),
            'break_start': safe_iso(c.break_start),
            'break_end': safe_iso(c.break_end),
            'date': safe_iso(c.date),
        })
    return JsonResponse({'success': True, 'data': data})

        
@csrf_exempt
def add_next_month_fee(request, customer_id):
    if request.method == 'POST':
        try:
            data = json.loads(request.body)
            customer = get_object_or_404(Customers, customer_id=customer_id)
            package = PackageAmounts.objects.get(package=data.get('package'))
            
            # Calculate next month's due date and month
            last_paid = datetime.strptime(data.get('last_paid'), '%Y-%m-%d').date()
            duration = package.duration
            due_date = last_paid + timedelta(days=30 * duration)
            month = data.get('month') if 'Monthly' in data.get('package') else None
            
            # Create new TblAddfees entry
            new_fee = TblAddfees.objects.create(
                name=customer.name,
                customerid=customer.customer_id,
                month=month,
                join_date=customer.date,
                date=last_paid,
                package=data.get('package'),
                amount=package.amount,
                due_date=due_date,
                last_paid=last_paid,
                status=1,  # Active
                transaction_type=data.get('transaction_type', 'Cash'),
                created_at=timezone.now(),
                updated_at=timezone.now()
            )
            
            # Update Customers table
            customer.package = data.get('package')
            customer.amount = package.amount
            customer.advance = data.get('advance', 0)
            customer.total_amount = float(data.get('amount', 0)) - float(data.get('advance', 0))
            customer.due_date = due_date
            customer.last_paid = last_paid
            customer.month = month
            customer.status = 1
            customer.transaction_type = data.get('transaction_type', 'Cash')
            customer.updated_at = timezone.now()
            customer.save()
            
            # Save to PaymentHistory
            PaymentHistory.objects.create(
                fee=new_fee,
                date=last_paid,
                amount=package.amount,
                method=data.get('transaction_type', 'Cash'),
                status='Completed',
                created_at=timezone.now()
            )
            
            return JsonResponse({'success': True, 'message': 'Next month fee added successfully'})
        except Exception as e:
            return JsonResponse({'success': False, 'message': str(e)}, status=400)
                                  
@login_required(login_url='admin_login')
@csrf_exempt
def edit_customer(request, customer_id):
    print(f"Editing customer with ID: {customer_id}")
    customer = get_object_or_404(Customers, id=customer_id)
    print(f"Fetched customer: {customer.name} (ID: {customer.id})")
    if request.method == 'GET':
        print(f"GET request for customer ID: {customer_id}")
        try:
            data = {
                'id': customer.id,
                'name': customer.name,
                'customer_id': customer.customer_id,
                'gender': customer.gender,
                'age': customer.age,
                'email': customer.email,
                'phone': customer.phone,
                'address': customer.address,
                'weight': customer.weight,
                'height': customer.height,
                'blood_group': customer.blood_group,
                'bp_sugar': customer.bp_sugar,
                'sugar': customer.sugar,
                'other_problems': customer.other_problems,
                'package': customer.package,
                'transaction_type': customer.transaction_type,
                'amount': str(customer.amount) if customer.amount else '',
                'advance': str(customer.advance) if customer.advance else '',
                'total_amount': str(customer.total_amount) if customer.total_amount else '',
                'date': customer.date.strftime('%Y-%m-%d') if customer.date else '',
                'due_date': customer.due_date.strftime('%Y-%m-%d') if customer.due_date else '',
                'last_paid': customer.last_paid.strftime('%Y-%m-%d') if customer.last_paid else '',
                'month': customer.month,
                'status': customer.status,
                'break_start': customer.break_start.strftime('%Y-%m-%d') if customer.break_start else '',
                'break_end': customer.break_end.strftime('%Y-%m-%d') if customer.break_end else '',
                'message_clicks': customer.message_clicks,
            }
            print(f"Fetched customer data: {data}")
            return JsonResponse({'success': True, 'data': data})
        except Exception as e:
            logger.error(f"Error fetching customer: {str(e)}")
            return JsonResponse({'success': False, 'message': f'Error fetching customer: {str(e)}'})

    elif request.method == 'POST':
        try:
            # Update customer fields
            customer.name = request.POST.get('name', customer.name)
            customer.customer_id = request.POST.get('customer_id', customer.customer_id)
            customer.gender = request.POST.get('gender', customer.gender)
            customer.age = int(request.POST.get('age')) if request.POST.get('age') and request.POST.get('age').isdigit() else customer.age
            customer.email = request.POST.get('email', customer.email)
            customer.phone = request.POST.get('phone', customer.phone)
            customer.address = request.POST.get('address', customer.address)
            customer.weight = request.POST.get('weight', customer.weight)
            customer.height = request.POST.get('height', customer.height)
            customer.blood_group = request.POST.get('blood_group', customer.blood_group)
            customer.bp_sugar = request.POST.get('bp_sugar', customer.bp_sugar)
            customer.sugar = request.POST.get('sugar', customer.sugar)
            customer.other_problems = request.POST.get('other_problems', customer.other_problems)
            customer.package = request.POST.get('package', customer.package)
            customer.transaction_type = request.POST.get('transaction_type', customer.transaction_type)

            # Handle amount fields
            amount_str = request.POST.get('amount')
            advance_str = request.POST.get('advance')
            total_amount_str = request.POST.get('total_amount')
            try:
                customer.amount = float(amount_str) if amount_str else customer.amount
            except (ValueError, TypeError):
                pass
            try:
                customer.advance = float(advance_str) if advance_str else customer.advance
            except (ValueError, TypeError):
                pass
            try:
                customer.total_amount = float(total_amount_str) if total_amount_str else customer.total_amount
            except (ValueError, TypeError):
                if customer.amount is not None and customer.advance is not None:
                    customer.total_amount = customer.amount - customer.advance

            # Handle date fields
            date_str = request.POST.get('date')
            if date_str:
                try:
                    customer.date = datetime.strptime(date_str, '%Y-%m-%d').date()
                except ValueError:
                    pass

            last_paid_str = request.POST.get('last_paid')
            if last_paid_str:
                try:
                    customer.last_paid = datetime.strptime(last_paid_str, '%Y-%m-%d').date()
                except ValueError:
                    pass

            due_date_str = request.POST.get('due_date')
            if due_date_str:
                try:
                    customer.due_date = datetime.strptime(due_date_str, '%Y-%m-%d').date()
                except ValueError:
                    pass
            elif customer.last_paid and customer.package:
                package = customer.package
                duration_months = PACKAGE_DURATIONS.get(package, 1)
                customer.due_date = customer.last_paid + timedelta(days=30 * duration_months)

            customer.month = request.POST.get('month', customer.month)
            customer.status = request.POST.get('status', customer.status)

            # Handle break dates
            break_start_str = request.POST.get('break_start')
            if break_start_str:
                try:
                    customer.break_start = datetime.strptime(break_start_str, '%Y-%m-%d').date()
                    customer.status = 3  # Set status to "On Break"
                except ValueError:
                    pass
            else:
                customer.break_start = None

            break_end_str = request.POST.get('break_end')
            if break_end_str:
                try:
                    customer.break_end = datetime.strptime(break_end_str, '%Y-%m-%d').date()
                except ValueError:
                    pass
            else:
                customer.break_end = None

            # Handle photo
            if 'photo' in request.FILES:
                if customer.photo:
                    customer.photo.delete()
                customer.photo = request.FILES['photo']
            elif request.POST.get('remove_photo') == '1':
                if customer.photo:
                    customer.photo.delete()
                    customer.photo = None

            customer.updated_at = timezone.now()
            customer.save()

            # Update or create TblAddfees record
            try:
                fee = TblAddfees.objects.filter(customer=customer).first()
                if fee:
                    # Update existing fee record
                    fee.name = customer.name
                    fee.month = customer.month or ''
                    fee.join_date = customer.date
                    fee.date = customer.date
                    fee.last_paid = customer.last_paid
                    fee.due_date = customer.due_date if customer.status != 3 else None
                    fee.package = customer.package
                    fee.amount = customer.amount
                    fee.advance = customer.advance
                    fee.total_amount = customer.total_amount
                    fee.status = customer.status
                    fee.transaction_type = customer.transaction_type
                    fee.phone = customer.phone
                    fee.email = customer.email
                    fee.updated_at = timezone.now()
                    fee.save()
                else:
                    # Create new fee record
                    TblAddfees.objects.create(
                        customer=customer,
                        name=customer.name,
                        month=customer.month or '',
                        join_date=customer.date,
                        date=customer.date,
                        last_paid=customer.last_paid,
                        due_date=customer.due_date if customer.status != 3 else None,
                        package=customer.package,
                        amount=customer.amount,
                        advance=customer.advance,
                        total_amount=customer.total_amount,
                        status=customer.status,
                        transaction_type=customer.transaction_type,
                        phone=customer.phone,
                        email=customer.email,
                        created_at=timezone.now(),
                        updated_at=timezone.now()
                    )
            except Exception as e:
                logger.error(f"Error updating/creating TblAddfees: {str(e)}")
                return JsonResponse({'success': False, 'message': f'Error updating fee record: {str(e)}'}, status=500)

            return JsonResponse({'success': True, 'message': 'Customer and fee updated successfully!'})

        except Exception as e:
            logger.error(f"Error updating customer: {str(e)}")
            return JsonResponse({'success': False, 'message': f'Error updating customer: {str(e)}'}, status=500)
        
@login_required(login_url='admin_login')
def delete_customer(request, customer_id):
    """Delete a customer and their associated fee records."""
    if request.method == 'POST':
        try:
            customer = get_object_or_404(Customers, id=customer_id)
            TblAddfees.objects.filter(customer_id=customer.customer_id).delete()  # Fix: Use customer_id
            customer.delete()
            return JsonResponse({
                'success': True,
                'message': 'Customer and associated fees deleted successfully'
            })
        except Exception as e:
            logger.error(f"Error deleting customer {customer_id}: {str(e)}", exc_info=True)
            return JsonResponse({
                'success': False,
                'message': f'Error deleting customer: {str(e)}'
            }, status=500)
    return JsonResponse({
        'success': False,
        'message': 'Invalid request method'
    }, status=400)
#==========================add expenses================#
    # views.py

def expenses(request):
    """Display expenses page with datatable"""
    return render(request, 'safe_admin/expenses.html')

def get_expenses_data(request):
    try:
        # Initialize filters
        date_range = request.GET.get('date_range', 'all')
        from_date = request.GET.get('from_date')
        to_date = request.GET.get('to_date')
        expense_category = request.GET.get('expense_category')
        expense_status = request.GET.get('expense_status')
        search_purchase = request.GET.get('search_purchase')

        # Base queryset
        queryset = TblExpense.objects.all()

        # Apply filters
        if date_range != 'all' and date_range != 'custom':
            today = timezone.now().date()
            if date_range == 'today':
                queryset = queryset.filter(date=today)
            elif date_range == 'week':
                week_start = today - timedelta(days=today.weekday())
                queryset = queryset.filter(date__gte=week_start, date__lte=today)
            elif date_range == 'month':
                month_start = today.replace(day=1)
                queryset = queryset.filter(date__gte=month_start, date__lte=today)
            elif date_range == 'quarter':
                quarter_start = today.replace(month=((today.month - 1) // 3) * 3 + 1, day=1)
                queryset = queryset.filter(date__gte=quarter_start, date__lte=today)
            elif date_range == 'year':
                year_start = today.replace(month=1, day=1)
                queryset = queryset.filter(date__gte=year_start, date__lte=today)
        elif date_range == 'custom' and from_date and to_date:
            try:
                from_date = datetime.strptime(from_date, '%Y-%m-%d').date()
                to_date = datetime.strptime(to_date, '%Y-%m-%d').date()
                queryset = queryset.filter(date__gte=from_date, date__lte=to_date)
            except ValueError:
                return JsonResponse({'success': False, 'message': 'Invalid date format'}, status=400)

        if expense_category and expense_category != 'all':
            queryset = queryset.filter(purchase=expense_category)

        if expense_status and expense_status != 'all':
            queryset = queryset.filter(status=expense_status)

        if search_purchase:
            queryset = queryset.filter(purchase__icontains=search_purchase)

        # Chart data for the last 12 months
        today = timezone.now().date()
        year_ago = today - timedelta(days=365)
        monthly_expenses = TblExpense.objects.filter(
            date__gte=year_ago
        ).annotate(
            expense_month=TruncMonth('date')  # Renamed to avoid conflicts
        ).values('expense_month').annotate(
            total=Sum('amount')
        ).order_by('expense_month')

        chart_data = {
            'labels': [item['expense_month'].strftime('%b %Y') if item['expense_month'] else 'No Data' for item in monthly_expenses],
            'values': [float(item['total'] or 0) for item in monthly_expenses]
        }

        # Ensure chart data is complete for 12 months
        from dateutil import rrule
        months = list(rrule.rrule(rrule.MONTHLY, dtstart=year_ago, until=today))
        labels = [month.strftime('%b %Y') for month in months]
        values = [0] * len(labels)
        for item in monthly_expenses:
            if item['expense_month']:
                month_str = item['expense_month'].strftime('%b %Y')
                if month_str in labels:
                    index = labels.index(month_str)
                    values[index] = float(item['total'] or 0)

        chart_data = {
            'labels': labels,
            'values': values
        }

        # Prepare response data
        data = []
        for expense in queryset:
            data.append({
                'id': expense.id,
                'expense_date': expense.date.isoformat() if expense.date else None,
                'purchase_item': expense.purchase or 'N/A',
                'description': expense.description or '',
                'amount': float(expense.amount) if expense.amount else 0.0,
                'status': str(expense.status),
            })

        return JsonResponse({
            'success': True,
            'data': data,
            'chart_data': chart_data
        })
    except Exception as e:
        print(f"Error in get_expenses_data: {str(e)}")
        return JsonResponse({'success': False, 'message': f'Server error: {str(e)}'}, status=500)
                
def add_expense(request):
    """Add new expense"""
    if request.method == 'POST':
        try:
            date = request.POST.get('date')
            purchase = request.POST.get('purchase')
            description = request.POST.get('description', '')
            amount = request.POST.get('amount')
            status = int(request.POST.get('status', 1))
            
            # Create new expense
            expense = TblExpense.objects.create(
                date=date,
                purchase=purchase,
                description=description,
                amount=amount,
                status=status,
                created_at=datetime.now(),
                updated_at=datetime.now()
            )
            
            return JsonResponse({
                'success': True,
                'message': 'Expense added successfully!',
                'expense_id': expense.id
            })
            
        except Exception as e:
            return JsonResponse({
                'success': False,
                'message': f'Error adding expense: {str(e)}'
            })
    
    # If GET request, redirect to expenses page
    return redirect('expenses')

def edit_expense(request, expense_id):
    expense = get_object_or_404(TblExpense, id=expense_id)
    if request.method == 'GET':
        return JsonResponse({
            'id': expense.id,
            'date': expense.date.strftime('%Y-%m-%d') if expense.date else '',
            'purchase': expense.purchase,
            'description': expense.description or '',
            'amount': str(expense.amount),
            'status': expense.status
        })
    elif request.method == 'POST':
        try:
            expense.date = request.POST.get('date')
            expense.purchase = request.POST.get('purchase')
            expense.description = request.POST.get('description', '')
            expense.amount = request.POST.get('amount')
            expense.status = int(request.POST.get('status', 1))
            expense.updated_at = datetime.now()
            expense.save()
            return JsonResponse({
                'success': True,
                'message': 'Expense updated successfully!'
            })
        except Exception as e:
            return JsonResponse({
                'success': False,
                'message': f'Error updating expense: {str(e)}'
            })
    return JsonResponse({'success': False, 'message': 'Invalid request method'})

def delete_expense(request, expense_id):
    if request.method == 'POST':
        try:
            expense = get_object_or_404(TblExpense, id=expense_id)
            expense.delete()
            return JsonResponse({
                'success': True,
                'message': 'Expense deleted successfully!'
            })
        except Exception as e:
            return JsonResponse({
                'success': False,
                'message': f'Error deleting expense: {str(e)}'
            })
    return JsonResponse({'success': False, 'message': 'Invalid request method'})
# ==========================Export Income================#

def export_income(request):

    response = HttpResponse(content_type='application/pdf')
    response['Content-Disposition'] = 'attachment; filename="income_report.pdf"'

    p = canvas.Canvas(response, pagesize=A4)
    width, height = A4

    # Title
    p.setFont("Helvetica-Bold", 16)
    p.drawCentredString(width / 2.0, height - 50, "Income Report")

    # Column headers
    p.setFont("Helvetica-Bold", 12)
    p.drawString(50, height - 100, "Date")
    p.drawString(150, height - 100, "Customer ID")
    p.drawString(300, height - 100, "Amount")

    y = height - 120
    p.setFont("Helvetica", 11)

    incomes = TblAddfees.objects.all().order_by('-date')
    for income in incomes:
        p.drawString(50, y, str(income.date.strftime("%Y-%m-%d") if income.date else ''))
        p.drawString(150, y, str(income.customer_id or ''))
        p.drawString(300, y, str(income.amount or ''))
        y -= 20

        # New page if too low
        if y < 50:
            p.showPage()
            y = height - 50
            p.setFont("Helvetica", 11)

    p.save()
    return response

from django.http import HttpResponse

def export_income_excel(request):
    response = HttpResponse(
        content_type='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    )
    response['Content-Disposition'] = f'attachment; filename=income_report_{datetime.date.today()}.xlsx'
    response.write("This would be your Excel data.")
    return response


@csrf_exempt
def get_income_data(request):
    if request.method == 'POST':
        try:
            filters = json.loads(request.body)

            month_str = filters.get('month')
            if month_str:
                # Example: '2025-08'
                try:
                    year, month_num = map(int, month_str.split('-'))
                    start_date = datetime(year, month_num, 1).date()
                    if month_num < 12:
                        end_date = datetime(year, month_num + 1, 1).date()
                    else:
                        end_date = datetime(year + 1, 1, 1).date()

                    income_records = TblAddfees.objects.filter(date__gte=start_date, date__lt=end_date)
                except Exception as e:
                    return JsonResponse({'error': f'Invalid month format: {e}'}, status=400)
            else:
                income_records = TblAddfees.objects.all()

            # Prepare response data
            data = []
            for record in income_records:
                payment_date = record.date.strftime('%Y-%m-%d') if record.date else ''
                data.append({
                    'id': record.id,
                    'customer_id': record.customerid,
                    'name': record.name,
                    'package': record.package,
                    'amount': float(record.amount),
                    'payment_date': payment_date,
                    'status': record.status
                })

            return JsonResponse({'data': data})
        
        except Exception as e:
            return JsonResponse({'error': f'Server error: {str(e)}'}, status=500)

    return JsonResponse({'error': 'Invalid request method'}, status=405)    
        #=========================Expense Report================#

def export_expenses(request):
    response = HttpResponse(content_type='application/pdf')
    response['Content-Disposition'] = 'attachment; filename="expense_report.pdf"'
    p = canvas.Canvas(response, pagesize=A4)
    width, height = A4
    p.setFont("Helvetica-Bold", 16)
    p.drawCentredString(width / 2.0, height - 50, "Expense Report")
    p.setFont("Helvetica-Bold", 12)
    p.drawString(50, height - 100, "Date")
    p.drawString(150, height - 100, "Purchase Item")
    p.drawString(300, height - 100, "Description")
    p.drawString(450, height - 100, "Amount")
    y = height - 120
    p.setFont("Helvetica", 11)
    expenses = TblExpense.objects.all().order_by('-date')
    for expense in expenses:
        p.drawString(50, y, expense.date.strftime("%Y-%m-%d") if expense.date else '')
        p.drawString(150, y, expense.purchase or 'N/A')
        p.drawString(300, y, expense.description or '')
        p.drawString(450, y, f"₹{expense.amount:,.2f}" if expense.amount else '0.00')
        y -= 20
        if y < 50:
            p.showPage()
            y = height - 50
    p.showPage()
    p.save()
    return response

from django.http import HttpResponse
import openpyxl
from .models import TblExpense

def export_expenses_excel(request):
    wb = openpyxl.Workbook()
    ws = wb.active
    ws.title = "Expense Report"
    ws.append(['Date', 'Purchase', 'Description', 'Amount'])
    expenses = TblExpense.objects.all()
    for expense in expenses:
        ws.append([
            expense.date.strftime("%Y-%m-%d"),
            expense.purchase,
            expense.description or '',
            float(expense.amount)
        ])
    response = HttpResponse(content_type='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
    response['Content-Disposition'] = 'attachment; filename=expense_report.xlsx'
    wb.save(response)
    return response

from dateutil import rrule

def get_expenses_data(request):
    try:
        # Initialize filters
        date_range = request.GET.get('date_range', 'all')
        from_date = request.GET.get('from_date')
        to_date = request.GET.get('to_date')
        expense_category = request.GET.get('expense_category')
        expense_status = request.GET.get('expense_status')
        search_purchase = request.GET.get('search_purchase')

        # Base queryset
        queryset = TblExpense.objects.all()

        # Apply filters
        if date_range != 'all' and date_range != 'custom':
            today = timezone.now().date()
            if date_range == 'today':
                queryset = queryset.filter(date=today)
            elif date_range == 'week':
                week_start = today - timedelta(days=today.weekday())
                queryset = queryset.filter(date__gte=week_start, date__lte=today)
            elif date_range == 'month':
                month_start = today.replace(day=1)
                queryset = queryset.filter(date__gte=month_start, date__lte=today)
            elif date_range == 'quarter':
                quarter_start = today.replace(month=((today.month - 1) // 3) * 3 + 1, day=1)
                queryset = queryset.filter(date__gte=quarter_start, date__lte=today)
            elif date_range == 'year':
                year_start = today.replace(month=1, day=1)
                queryset = queryset.filter(date__gte=year_start, date__lte=today)
        elif date_range == 'custom' and from_date and to_date:
            try:
                from_date = datetime.strptime(from_date, '%Y-%m-%d').date()
                to_date = datetime.strptime(to_date, '%Y-%m-%d').date()
                queryset = queryset.filter(date__gte=from_date, date__lte=to_date)
            except ValueError:
                return JsonResponse({'success': False, 'message': 'Invalid date format'}, status=400)

        if expense_category and expense_category != 'all':
            queryset = queryset.filter(purchase=expense_category)

        if expense_status and expense_status != 'all':
            queryset = queryset.filter(status=expense_status)

        if search_purchase:
            queryset = queryset.filter(purchase__icontains=search_purchase)

        # Chart data for the last 12 months
        today = timezone.now().date()
        year_ago = today - timedelta(days=365)
        monthly_expenses = TblExpense.objects.filter(
            date__gte=year_ago
        ).annotate(
            expense_month=TruncMonth('date')
        ).values('expense_month').annotate(
            total=Sum('amount')
        ).order_by('expense_month')

        chart_data = {
            'labels': [item['expense_month'].strftime('%b %Y') if item['expense_month'] else 'No Data' for item in monthly_expenses],
            'values': [float(item['total'] or 0) for item in monthly_expenses]
        }

        # Ensure chart data is complete for 12 months
        months = list(rrule.rrule(rrule.MONTHLY, dtstart=year_ago, until=today))
        labels = [month.strftime('%b %Y') for month in months]
        values = [0] * len(labels)
        for item in monthly_expenses:
            if item['expense_month']:
                month_str = item['expense_month'].strftime('%b %Y')
                if month_str in labels:
                    index = labels.index(month_str)
                    values[index] = float(item['total'] or 0)

        chart_data = {
            'labels': labels,
            'values': values
        }

        # Prepare response data
        data = []
        for expense in queryset:
            data.append({
                'id': expense.id,
                'expense_date': expense.date.isoformat() if expense.date else None,
                'purchase': expense.purchase or 'N/A',
                'description': expense.description or '',
                'amount': float(expense.amount) if expense.amount else 0.0,
                'status': 'Active' if expense.status == 1 else 'Inactive',
                'created_at': expense.created_at.isoformat() if expense.created_at else None,
                'actions': f'<button class="btn btn-sm btn-primary edit-btn me-1" data-id="{expense.id}"><i class="fas fa-edit"></i></button>'
                          f'<button class="btn btn-sm btn-danger delete-btn" data-id="{expense.id}"><i class="fas fa-trash"></i></button>'
            })

        return JsonResponse({
            'success': True,
            'data': data,
            'chart_data': chart_data
        })
    except Exception as e:
        print(f"Error in get_expenses_data: {str(e)}")
        return JsonResponse({'success': False, 'message': f'Server error: {str(e)}'}, status=500)
    
@login_required(login_url='admin_login')
@never_cache
def profit_loss_report(request):
    try:
        month = request.GET.get('month')  # Format: YYYY-MM
        if not month:
            return JsonResponse({'success': False, 'message': 'Month is required'})

        year, month_num = map(int, month.split('-'))
        start_date = date(year, month_num, 1)
        if month_num == 12:
            end_date = date(year + 1, 1, 1)
        else:
            end_date = date(year, month_num + 1, 1)

        # ✅ Corrected field names
        income_records = TblAddfees.objects.filter(
            date__gte=start_date,
            date__lt=end_date
        ).values('customer_id', 'package', 'amount', 'date', 'status')
        
        income_details = []
        for record in income_records:
            income_details.append({
                'customer_id': record['customer_id'],
                'package': record['package'],
                'amount': float(record['amount']),
                'date': record['date'].isoformat() if record['date'] else None,
                'status': record['status']
            })

        total_income = sum(record['amount'] for record in income_details)

        expense_records = TblExpense.objects.filter(
            date__gte=start_date,
            date__lt=end_date
        ).values('purchase', 'description', 'amount', 'date')
        
        expense_details = []
        for record in expense_records:
            expense_details.append({
                'purchase': record['purchase'],
                'description': record['description'],
                'amount': float(record['amount']),
                'date': record['date'].isoformat() if record['date'] else None
            })

        total_expenses = sum(record['amount'] for record in expense_details)

        profit_loss = total_income - total_expenses
        status = 'Profit' if profit_loss >= 0 else 'Loss'

        return JsonResponse({
            'success': True,
            'month': month,
            'total_income': total_income,
            'total_expenses': total_expenses,
            'profit_loss': profit_loss,
            'status': status,
            'income_details': income_details,
            'expense_details': expense_details
        })
    except Exception as e:
        import traceback
        traceback.print_exc()
        return JsonResponse({
            'success': False,
            'message': str(e)
        })
                            
        #=========================Fees Views================#
from .models import TblAddfees

from django.shortcuts import render
from django.http import JsonResponse
from .models import TblAddfees
import json


def get_dashboard_data(request):
    try:
        # Aggregate revenue by month
        revenue_data = TblAddfees.objects.annotate(
            month_truncated=TruncMonth('date')
        ).values('month_truncated').annotate(
            total=Sum('amount')
        ).order_by('month_truncated')

        # Aggregate expenses by month
        expense_data = TblExpense.objects.annotate(
            month_truncated=TruncMonth('date')
        ).values('month_truncated').annotate(
            total=Sum('amount')
        ).order_by('month_truncated')

        # Prepare data for chart
        months_set = set(
            entry['month_truncated'].strftime('%B %Y') for entry in revenue_data
        ).union(
            entry['month_truncated'].strftime('%B %Y') for entry in expense_data
        )
        months = sorted(months_set)

        revenue = [0] * len(months)
        expenses = [0] * len(months)

        # Map revenue data
        for entry in revenue_data:
            month_str = entry['month_truncated'].strftime('%B %Y')
            index = months.index(month_str)
            revenue[index] = float(entry['total'])

        # Map expense data
        for entry in expense_data:
            month_str = entry['month_truncated'].strftime('%B %Y')
            index = months.index(month_str)
            expenses[index] = float(entry['total'])

        data = {
            'months': months,
            'revenue': revenue,
            'expenses': expenses
        }

        return JsonResponse({
            'success': True,
            'data': data
        })

    except Exception as e:
        return JsonResponse({
            'success': False,
            'message': str(e)
        })
    
        # Fee Management Views
@login_required(login_url='admin_login')
def manage_fees(request):
    """Render the manage fees page for updating package amounts."""
    return render(request, 'safe_admin/manage_fees.html')

@login_required(login_url='admin_login')
def get_packages(request):
    """Fetch all packages from PackageAmounts"""
    try:
        packages = PackageAmounts.objects.all().order_by('package')
        package_list = [{
            'package': pkg.package,
            'amount': float(pkg.amount),
            'duration': pkg.duration
        } for pkg in packages]
        
        return JsonResponse({
            'success': True,
            'packages': package_list,
            'package_config': {pkg.package: {
                'amount': float(pkg.amount),
                'duration': pkg.duration
            } for pkg in packages}
        })
    except Exception as e:
        logger.error(f"Error fetching packages: {str(e)}", exc_info=True)
        return JsonResponse({
            'success': False,
            'message': f'Error fetching packages: {str(e)}'
        }, status=500)
        
@login_required(login_url='admin_login')
def update_package_amount(request):
    """Update the amount for a specific package in PackageAmounts without affecting existing records."""
    if request.method == 'POST':
        try:
            data = json.loads(request.body)
            package_name = data.get('package')
            new_amount = data.get('amount')

            if not package_name:
                return JsonResponse({
                    'success': False,
                    'message': 'Package name is required'
                }, status=400)

            try:
                new_amount = float(new_amount)
                if new_amount <= 0:
                    return JsonResponse({
                        'success': False,
                        'message': 'Amount must be greater than zero'
                    }, status=400)
            except (TypeError, ValueError):
                return JsonResponse({
                    'success': False,
                    'message': 'Invalid amount format'
                }, status=400)

            # Update or create the package amount in PackageAmounts
            package_amount, created = PackageAmounts.objects.update_or_create(
                package=package_name,
                defaults={
                    'amount': new_amount,
                    'updated_at': timezone.now()
                }
            )

            action = "created" if created else "updated"
            logger.info(f"{action.capitalize()} package amount for {package_name} to ₹{new_amount}")
            
            # Return the updated package config
            return JsonResponse({
                'success': True,
                'message': f'Amount for {package_name} {action} successfully.',
                'package': package_name,
                'new_amount': new_amount,
                'package_config': {
                    'amount': float(new_amount),
                    'advance': float(new_amount) * 0.5,
                    'duration': 'month'
                }
            })
        except json.JSONDecodeError:
            return JsonResponse({
                'success': False,
                'message': 'Invalid JSON data'
            }, status=400)
        except Exception as e:
            logger.error(f"Error updating package amount: {str(e)}", exc_info=True)
            return JsonResponse({
                'success': False,
                'message': f'Error updating amount: {str(e)}'
            }, status=500)
    return JsonResponse({
        'success': False,
        'message': 'Invalid request method'
    }, status=405)

    from django.shortcuts import render, get_object_or_404
from django.http import JsonResponse
from django.views.decorators.csrf import csrf_exempt
from django.views.decorators.http import require_http_methods
from django.contrib import messages
import json
from .models import TblAddfees

def fees_page(request):
    """Render the fees management page"""
    return render(request, 'fees/fees_page.html')


from django.http import JsonResponse
from django.shortcuts import get_object_or_404

def get_fees_data(request, customer_id):
    try:
        customer = Customers.objects.get(id=customer_id)
        print(f"Fetching fees data for customer: {customer.name} (ID: {customer_id})")
        data = {
            'id': customer.id,
            'name': customer.name,
            'customer_id': customer.customer_id,
            'gender': customer.gender,
            'date': customer.date.strftime('%Y-%m-%d') if customer.date else None,
            'package': customer.package,
            'amount': float(customer.amount),
            'due_date': customer.due_date.strftime('%Y-%m-%d') if customer.due_date else None,
            'status': customer.status,
        }
        return JsonResponse({'success': True, 'data': data})  # ✅ single object
    except Customers.DoesNotExist:
        return JsonResponse({'success': False, 'error': 'Customer not found'})


def get_all_fees_data(request):
    fees = TblAddfees.objects.select_related('customer').all()
    data = []

    for fee in fees:
        data.append({
            'id': fee.id,
            'name': fee.customer.name if fee.customer else 'N/A',
            'package': fee.package,
            'amount': float(fee.amount),
            'due_date': fee.due_date.strftime('%Y-%m-%d') if fee.due_date else '',
            'month': fee.month,
            'status': 'Paid' if fee.status == 1 else 'Unpaid',
        })

    return JsonResponse({'data': data})

@csrf_exempt
def update_fee(request):
    if request.method == 'POST':
        print("Received request to update fee", request.body)
        try:
            print("Processing update_fee request")
            data = json.loads(request.body)
            print(f"Data received for fee update: {data}")
            fee_id = data.get('id')
            print(f"Fee ID to update: {fee_id}")

            fee = TblAddfees.objects.get(customer_id=fee_id)
            print(f"Found fee record: {fee}")
            # Save old values for comparison if needed
            old_amount = fee.amount
            old_last_paid = fee.last_paid

            # Update fee fields
            fee.amount = data.get('amount')
            fee.package = data.get('package')
            fee.date = parse_date(data.get('join_date'))
            fee.last_paid = parse_date(data.get('last_paid')) if data.get('last_paid') else None
            fee.due_date = parse_date(data.get('due_date')) if data.get('due_date') else None
            fee.month = data.get('month')
            fee.status = int(data.get('status'))
            fee.transaction_type = data.get('transaction_type')
            fee.save()
            print(f"Fee updated: {fee_id}, Amount: {fee.amount}, Last Paid: {fee.last_paid}")
            # ✅ Sync due date with customer
            if fee.customer:
                fee.customer.due_date = fee.due_date
                fee.customer.save(update_fields=['due_date'])
            print(f"Fee updated: {fee_id}, Amount: {fee.amount}, Last Paid: {fee.last_paid}")
            # ✅ Add a record in PaymentHistory only if last_paid or amount is updated
            if fee.last_paid and fee.amount:
                PaymentHistory.objects.create(
                    fee=fee,
                    date=fee.last_paid,
                    amount=fee.amount,
                    method=fee.transaction_type or 'Unknown',
                    status='Completed'
                )
            print(f"Fee updated successfully: {fee_id}")
            return JsonResponse({'success': True})

        except Exception as e:
            print(f"Error updating fee: {str(e)}")
            return JsonResponse({'success': False, 'error': str(e)})
                        
@csrf_exempt
@require_http_methods(["DELETE"])
@login_required(login_url='admin_login')
def delete_fee(request, fee):
    """Delete a single fee record."""
    if request.method == 'POST':
        try:
            fee = get_object_or_404(TblAddfees, id=fee)
            fee.delete()
            return JsonResponse({
                'success': True,
                'message': 'Fee record deleted successfully'
            })
        except Exception as e:
            logger.error(f"Error deleting fee {fee}: {str(e)}", exc_info=True)
            return JsonResponse({
                'success': False,
                'message': f'Error deleting fee: {str(e)}'
            }, status=500)
    return JsonResponse({
        'success': False,
        'message': 'Invalid request method'
    }, status=400)

from django.http import JsonResponse
from django.views.decorators.csrf import csrf_exempt

@login_required(login_url='admin_login')
@csrf_exempt
def edit_fee(request, fee_id):
    if request.method == 'GET':
        fee = get_object_or_404(TblAddfees, id=fee_id)
        packages = PackageAmounts.objects.all().values('package', 'amount', 'duration')
        data = {
            'id': fee.id,
            'customerid': fee.customer,
            'package': fee.package,
            'amount': str(fee.amount),
            'date': fee.date.isoformat() if fee.date else None,
            'due_date': fee.due_date.isoformat() if fee.due_date else None,
            'month': fee.month,
            'status': fee.status,
            'transaction_type': fee.transaction_type or 'Cash',
        }
        return JsonResponse({'success': True, 'data': data, 'packages': list(packages)})

    elif request.method == 'POST':
        try:
            data = json.loads(request.body)
            logger.info(f"Received data for fee {fee_id}: {data}")
            fee = get_object_or_404(TblAddfees, id=fee_id)

            # Save old values
            original_last_paid = getattr(fee, 'last_paid', None)
            original_amount = fee.amount
            original_transaction_type = fee.transaction_type

            fee.package = data.get('package', fee.package)

            try:
                fee.amount = float(data.get('amount', fee.amount))
            except (ValueError, TypeError):
                logger.warning(f"Invalid amount received: {data.get('amount')}")

            for field, value in [('date', data.get('date')),
                                 ('due_date', data.get('due_date')),
                                 ('last_paid', data.get('last_paid'))]:
                if value:
                    try:
                        setattr(fee, field, datetime.strptime(value, '%Y-%m-%d').date())
                    except ValueError:
                        logger.warning(f"Invalid date format for {field}: {value}")
                        setattr(fee, field, None)
                else:
                    setattr(fee, field, None)

            fee.month = data.get('month', fee.month)

            try:
                fee.status = int(data.get('status', fee.status))
            except (ValueError, TypeError):
                logger.warning(f"Invalid status received: {data.get('status')}")

            fee.transaction_type = data.get('transaction_type', fee.transaction_type) or 'Cash'
            fee.updated_at = timezone.now()
            fee.save()

            if (original_last_paid != getattr(fee, 'last_paid', None) or
                original_amount != fee.amount or
                original_transaction_type != fee.transaction_type):
                PaymentHistory.objects.create(
                    fee=fee,
                    date=fee.last_paid or timezone.now().date(),
                    amount=fee.amount,
                    method=fee.transaction_type or 'Cash',
                    status='Completed',
                    created_at=timezone.now()
                )

            return JsonResponse({'success': True, 'message': 'Fee updated successfully'})

        except json.JSONDecodeError:
            logger.error("Invalid JSON data received")
            return JsonResponse({'success': False, 'message': 'Invalid JSON data'}, status=400)
        except Exception as e:
            logger.error(f"Error updating fee {fee_id}: {str(e)}", exc_info=True)
            return JsonResponse({'success': False, 'message': f'Internal server error: {str(e)}'}, status=500)

    return JsonResponse({'success': False, 'message': 'Method not allowed'}, status=405)
                                                                        
@csrf_exempt
@login_required(login_url='admin_login')
def break_member(request, customer_id):
    if request.method == 'POST':
        try:
            customer = get_object_or_404(Customers, id=customer_id)
            customer.status = 3  # On break
            customer.break_start = datetime.now().date()
            customer.break_end = None  # Clear previous value if any
            customer.save()

            feetable = get_object_or_404(TblAddfees, customer_id=customer_id)
            print(f"Putting {customer.name} on break. Current fee status: {feetable.id}")
            feetable.status = 3  # On break
            feetable.save()

            return JsonResponse({'success': True, 'message': f'{customer.name} has been put on break successfully!'})
        except Exception as e:
            return JsonResponse({'success': False, 'message': f'Error putting member on break: {str(e)}'}, status=500)
    return JsonResponse({'success': False, 'message': 'Invalid request method'}, status=400)

@login_required(login_url='admin_login')
def add_fee(request):
    if request.method == 'POST':
        try:
            data = json.loads(request.body)
            fee = TblAddfees(
                name=data.get('name'),
                customerid=data.get('customerid'),
                package=data.get('package'),
                amount=data.get('amount'),
                date=datetime.strptime(data.get('date'), '%Y-%m-%d').date() if data.get('date') else None,
                due_date=datetime.strptime(data.get('due_date'), '%Y-%m-%d').date() if data.get('due_date') else None,
                month=data.get('month'),
                status=int(data.get('status')),
                created_at=timezone.now(),
                updated_at=timezone.now()
            )
            fee.save()

            # Record initial payment history
            PaymentHistory.objects.create(
                fee=fee,
                date=fee.date,
                amount=fee.amount,
                transaction_type=data.get('transaction_type', 'Cash'),
                status='Completed'
            )
            
            return JsonResponse({'success': True, 'message': 'Fee added and payment recorded successfully!'})
        except Exception as e:
            logger.error(f"Error adding fee: {str(e)}")
            return JsonResponse({'success': False, 'message': str(e)})
    return JsonResponse({'success': False, 'message': 'Invalid request method'})

@csrf_exempt
@login_required(login_url='admin_login')
def unbreak_member(request, customer_id):
    if request.method == 'POST':
        try:
            customer = get_object_or_404(Customers, id=customer_id)

            if not customer.break_start:
                return JsonResponse({
                    'success': False,
                    'message': 'This member is not currently on break.'
                })

            break_end = datetime.now().date()
            break_days = (break_end - customer.break_start).days + 1  # Include start day

            # Extend due date if exists
            if customer.due_date:
                customer.due_date += timedelta(days=break_days)

            skipped_range = f"{customer.break_start} to {break_end}"

            # Update Customers table
            customer.status = 1  # Active
            customer.break_end = break_end
            customer.break_start = None
            customer.updated_at = datetime.now()
            customer.save(update_fields=[
                'status', 'break_start', 'break_end', 'due_date', 'updated_at'
            ])

            # Update TblAddfees table (keep statuses in sync)
            TblAddfees.objects.filter(customer_id=customer.id).update(
                status=1
            )

            # Prepare full updated data for DataTables
            updated_data = {
                'id': customer.id,
                'customer_id': customer.customer_id,
                'name': customer.name,
                'gender': customer.gender,
                'age': customer.age,
                'email': customer.email,
                'phone': customer.phone,
                'package': customer.package,
                'amount': float(customer.amount) if customer.amount else None,
                'advance': float(customer.advance) if customer.advance else None,
                'total_amount': float(customer.total_amount) if customer.total_amount else None,
                'due_date': customer.due_date.strftime('%Y-%m-%d') if customer.due_date else None,
                'last_paid': customer.last_paid.strftime('%Y-%m-%d') if customer.last_paid else None,
                'status': customer.status,
                'break_start': None,
                'break_end': customer.break_end.strftime('%Y-%m-%d') if customer.break_end else None
            }

            return JsonResponse({
                'success': True,
                'message': f'{customer.name} reactivated. Skipped {break_days} days: {skipped_range}',
                'skipped_dates': skipped_range,
                'updated_data': updated_data
            })

        except Exception as e:
            return JsonResponse({'success': False, 'message': f'Error: {str(e)}'}, status=500)

    return JsonResponse({'success': False, 'message': 'Invalid request method'}, status=400)

@login_required(login_url='admin_login')
@csrf_exempt
def send_whatsapp(request):
    if request.method == 'POST':
        try:
            data = json.loads(request.body)
            print(f"Received data for WhatsApp message: {request.body}")
            customer_id = data.get('customer_id')
            phone = data.get('phone')
            message = data.get('message')
            customer = get_object_or_404(Customers, id=customer_id)
            customer.message_clicks = customer.message_clicks + 1 if customer.message_clicks else 1
            customer.save()
            
            encoded_message = urllib.parse.quote(message)
            
            whatsapp_url = f"https://wa.me/+91{phone}?text={encoded_message}"
            return JsonResponse({'success': True, 'whatsapp_url': whatsapp_url})
        except Exception as e:
            logger.error(f"Error in send_whatsapp: {str(e)}")
            return JsonResponse({'success': False, 'message': f'Error sending WhatsApp message: {str(e)}'}, status=500)
    return JsonResponse({'success': False, 'message': 'Invalid request method'}, status=400)

@login_required(login_url='admin_login')
@csrf_exempt
def send_overdue_notice(request, customer_id):
    if request.method == 'POST':
        try:
            customer = get_object_or_404(Customers, id=customer_id)

            # Make sure the phone number is in correct format
            phone = re.sub(r'\D', '', customer.phone or '')  # remove non-digits
            if not phone.startswith('91'):  # default to India code
                phone = '91' + phone

            # WhatsApp message
            message = (
                f"Dear {customer.name},\n\n"
                f"Your gym membership payment is overdue.\n"
                f"Package: {customer.package}\n"
                f"Amount: ₹{customer.total_amount}\n"
                f"Due Date: {customer.due_date.strftime('%d/%m/%Y') if customer.due_date else 'N/A'}\n\n"
                f"Please settle the payment at the earliest.\n\nThank you!"
            )

            encoded_message = urllib.parse.quote(message)
            whatsapp_url = f"https://api.whatsapp.com/send?phone={phone}&text={encoded_message}"

            # Update message clicks count
            customer.message_clicks = (customer.message_clicks or 0) + 1
            customer.save()

            return JsonResponse({'success': True, 'whatsapp_url': whatsapp_url})
        except Exception as e:
            return JsonResponse({'success': False, 'message': str(e)}, status=500)

    return JsonResponse({'success': False, 'message': 'Invalid request method'}, status=400)

logger = logging.getLogger(__name__)

@login_required(login_url='myadmin')
def generate_receipt(request, customer_id):
    try:
        customer = get_object_or_404(Customers, id=customer_id)
        logger.debug(f"Customer data: {customer.__dict__}")
        total_amount = float(customer.total_amount) if customer.total_amount is not None else 0.0
        # Sanitize phone number (keep only digits and prepend +91)
        phone = ''.join(filter(str.isdigit, customer.phone or '')) if customer.phone else ''
        if phone:  # Only prepend +91 if phone is not empty
            phone = f"+91{phone}"
        # Escape customer name for JavaScript
        safe_name = json.dumps(customer.name or 'Unknown')  # Escapes quotes and special characters
        # Generate WhatsApp URL
        whatsapp_message = (
            f"Payment Receipt\nName: {customer.name or 'Unknown'}\n"
            f"Amount: ₹{total_amount:.2f}\nThank you for your payment to Safe Gym!"
        )
        whatsapp_url = f"https://wa.me/{phone}?text={urllib.parse.quote(whatsapp_message)}" if phone else "#"
        receipt_html = f"""
        <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Payment Receipt - Safe Gym</title>
                <style>
                    body {{
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 20px;
                        background-color: #f9f9f9;
                    }}
                    .receipt {{
                        max-width: 600px;
                        margin: 0 auto;
                        border: 1px solid #ccc;
                        padding: 20px;
                        background-color: white;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        position: relative;
                    }}
                    .header {{
                        text-align: center;
                        margin-bottom: 20px;
                    }}
                    .header img {{
                        width: 150px;
                        height: auto;
                    }}
                    .header h2 {{
                        color: #2c3e50;
                        margin: 5px 0;
                    }}
                    .header p {{
                        color: #7f8c8d;
                        font-size: 14px;
                    }}
                    .details-table, .amount-table {{
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 20px;
                    }}
                    .details-table td, .amount-table td {{
                        padding: 10px;
                        border: 1px solid #ddd;
                    }}
                    .details-table td:first-child, .amount-table td:first-child {{
                        font-weight: bold;
                    }}
                    .amount-table .total-row {{
                        font-weight: bold;
                    }}
                    .received {{
                        margin-bottom: 20px;
                        font-weight: bold;
                    }}
                    .signatures {{
                        display: flex;
                        justify-content: space-between;
                        margin-top: 20px;
                    }}
                    .signatures span {{
                        border-bottom: 1px solid #000;
                        width: 200px;
                        text-align: center;
                    }}
                    .whatsapp-btn {{
                        display: inline-block;
                        padding: 8px 15px;
                        background-color: #25D366;
                        color: white;
                        text-decoration: none;
                        border-radius: 5px;
                        position: absolute;
                        top: 20px;
                        right: 20px;
                    }}
                    .whatsapp-btn:hover {{
                        background-color: #20b855;
                    }}
                </style>
                <script>
                    // eslint-disable-next-line no-unused-vars
                    function sendWhatsApp(phone, amount, name) {{
                        if (!phone) {{
                            alert('Phone number not available for this customer.');
                            return false;
                        }}
                        const message = `Payment Receipt\\nName: ${{name}}\\nAmount: ₹${{Number(amount).toFixed(2)}}\\nThank you for your payment to Safe Gym!`;
                        const whatsappUrl = `https://wa.me/${{phone}}?text=${{encodeURIComponent(message)}}`;
                        window.open(whatsappUrl, '_blank');
                        return false; // Prevent default link behavior
                    }}
                </script>
            </head>
            <body>
                <div class="receipt">
                    <a href="{whatsapp_url}" class="whatsapp-btn" onclick="return sendWhatsApp('{phone}', {total_amount}, {safe_name})">Send via WhatsApp</a>
                    <div class="header">
                        <img src="/static/img/logo/safe.jpg" alt="Safe Gym Logo">
                        <h2>Safe Gym</h2>
                        <p>Admin Dashboard</p>
                    </div>
                    <table class="details-table">
                        <tr>
                            <td>Receipt No:</td>
                            <td>{customer.id}</td>
                            <td>Date:</td>
                            <td>{timezone.now().strftime('%d/%m/%Y %I:%M %p')}</td>
                        </tr>
                        <tr>
                            <td>Name:</td>
                            <td>{customer.name or 'Unknown'}</td>
                            <td>Member no:</td>
                            <td>{customer.customer_id or 'Unknown'}</td>
                        </tr>
                    </table>
                    <table class="amount-table">
                        <tr>
                            <td>Sr No</td>
                            <td>Work out Type</td>
                            <td>Date of Transaction</td>
                            <td>Amount</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>{customer.package or 'GYM'}</td>
                            <td>From {customer.last_paid.strftime('%d/%m/%Y') if customer.last_paid else 'N/A'} to {customer.due_date.strftime('%d/%m/%Y') if customer.due_date else 'N/A'}</td>
                            <td>₹{total_amount:.2f}</td>
                        </tr>
                        <tr class="total-row">
                            <td></td>
                            <td></td>
                            <td>TOTAL</td>
                            <td>₹{total_amount:.2f}</td>
                        </tr>
                    </table>
                    <div class="received">Received Rupees: {number_to_words(total_amount)}</div>
                    <div>By: cash</div>
                    <div class="signatures">
                        <span>Received by: User</span>
                        <span>Receiver's Signature</span>
                    </div>
                </div>
            </body>
        </html>
        """
        return JsonResponse({
            'success': True,
            'receipt_html': receipt_html,
            'customer': {
                'name': customer.name or 'Unknown',
                'customer_id': customer.customer_id or 'Unknown',
                'phone': phone or '',
                'total_amount': total_amount,
                'last_paid': customer.last_paid.strftime('%d/%m/%Y') if customer.last_paid else 'N/A',
                'due_date': customer.due_date.strftime('%d/%m/%Y') if customer.due_date else 'N/A',
                'package': customer.package or 'GYM'
            }
        })
    except Exception as e:
        logger.error(f"Error in generate_receipt: {str(e)}")
        return JsonResponse({'success': False, 'message': f'Error generating receipt: {str(e)}'}, status=500)

def number_to_words(amount):
    try:
        from num2words import num2words
        return num2words(float(amount), lang='en_IN').title() + ' Only'
    except ImportError:
        logger.warning("num2words library not installed, falling back to numeric format")
        return f"{float(amount):.2f} Rupees"
    except (ValueError, TypeError):
        logger.error(f"Invalid amount format: {amount}")
        return f"{float(amount) if amount is not None else 0.0:.2f} Rupees"        
            
@login_required(login_url='admin_login')
def payment_history(request, fee_id):
    try:
        fee_obj = get_object_or_404(TblAddfees, customer_id=fee_id)
        print(f"Fetching payment history for fee ID: {fee_id}, Fee Object: {fee_obj}")
        history = PaymentHistory.objects.filter(fee=fee_obj).values(
            'date', 'amount', 'method', 'status', 'created_at'
        )
        print(f"Payment history records found: {len(history)}")
        response_history = []
        for entry in history:
            response_history.append({
                'date': entry['date'].isoformat() if entry['date'] else None,
                'amount': str(entry['amount']),
                'method': entry['method'],
                'status': entry['status'],
                'created_at': entry['created_at'].isoformat() if entry['created_at'] else None
            })
        print(f"Formatted payment history: {response_history}")
        return JsonResponse({
            'success': True,
            'history': response_history,
            'name': fee_obj.name  # Ensure TblAddfees has a 'name' field
        })

    except Exception as e:
        import traceback
        traceback.print_exc()
        logger.error(f"Error fetching payment history for fee {fee_id}: {str(e)}", exc_info=True)
        return JsonResponse({'success': False, 'message': str(e)}, status=500)
                                        
def form(request):
    """
    Public-facing registration form for users to sign up.
    Creates a record in Customers and TblAddfees models without Users table interaction.
    """
    if request.method == 'POST':
        try:
            # Get form data
            name = request.POST.get('name')
            email = request.POST.get('email')
            phone = request.POST.get('phone')
            password = request.POST.get('password')
            confirm_password = request.POST.get('confirm_password')
            gender = request.POST.get('gender')
            age = request.POST.get('age')
            package = request.POST.get('package')
            date = request.POST.get('date')
            due_date = request.POST.get('due_date')
            month = request.POST.get('month')
            photo = request.FILES.get('photo')

            # Validate required fields
            required_fields = {'name': name, 'email': email, 'phone': phone, 'password': password, 'package': package, 'date': date}
            for field_name, field_value in required_fields.items():
                if not field_value or field_value.strip() == '':
                    return JsonResponse({
                        'success': False,
                        'message': f'{field_name.capitalize()} is required.'
                    }, status=400)

            # Validate password
            if password != confirm_password:
                return JsonResponse({
                    'success': False,
                    'message': 'Passwords do not match.'
                }, status=400)

            # Check if email already exists in Customers
            if Customers.objects.filter(email=email).exists():
                return JsonResponse({
                    'success': False,
                    'message': 'Email is already registered.'
                }, status=400)

            # Get package amount
            try:
                package_amount = PackageAmounts.objects.get(package=package)
                amount = float(package_amount.amount)
                advance = amount * 0.5  # Assume 50% advance
                total_amount = amount - advance
            except PackageAmounts.DoesNotExist:
                return JsonResponse({
                    'success': False,
                    'message': f'Package "{package}" not found.'
                }, status=400)

            # Create Customer
            customer = Customers(
                name=name,
                email=email,
                phone=phone,
                gender=gender if gender else None,
                age=int(age) if age and age.isdigit() else None,
                package=package,
                amount=amount,
                advance=advance,
                total_amount=total_amount,
                date=date,
                due_date=due_date if due_date else None,
                month=month if month else None,
                status=1,  # Active by default
                created_at=timezone.now(),
                updated_at=timezone.now(),
                photo=photo if photo else None
            )
            customer.save()

            # Create TblAddfees entry
            TblAddfees.objects.create(
                name=name,
                customerid=str(customer.id),
                month=month if month else '',
                join_date=date,
                date=date,
                package=package,
                amount=amount,
                due_date=due_date if due_date else None,
                status=1,
                created_at=timezone.now(),
                updated_at=timezone.now()
            )

            return JsonResponse({
                'success': True,
                'message': 'Registration successful!',
                'customer_id': customer.id
            })

        except Exception as e:
            logger.error(f"Error in form registration: {str(e)}", exc_info=True)
            return JsonResponse({
                'success': False,
                'message': f'Error during registration: {str(e)}'
            }, status=500)

    # For GET request, render the form template
    return render(request, 'safe_user/form.html', {'is_admin': False})