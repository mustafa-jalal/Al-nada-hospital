@extends('layouts.app')

@section('content')

    <div class="card">
        <h2>مرحباً، مصطفى</h2>
        @if(isset($error))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: '<%= error %>',
                    confirmButtonText: 'حسناً'
                });
            });
        </script>
        @endif

        <button id="checkInPatientBtn">اضافة مريض جديد</button>

        <div class="modal" id="checkInPatientModal">
            <div class="modal-content">
                <span class="close" id="closeModal">&times;</span>
                <h3>تسجيل دخول مريض جديد</h3>
                <form id="checkInPatientForm">
                    <div class="input-group">
                        <input type="text" name="name" placeholder="اسم المريض" required />
                    </div>
                    <div class="input-group">
                        <input type="text" name="national_number" placeholder="الرقم القومي" required />
                    </div>
                    <div class="input-group">
                        <input type="tel" name="phone" placeholder="رقم الهاتف" />
                    </div>
                    <div class="input-group">
                        <input type="text" name="address" placeholder="العنوان" />
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <button type="submit">تسجيل الدخول</button>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>قائمة المرضى </h2>
        <table class="patient-table">
            <thead>
            <tr>
                <th>الرقم التعريفي</th>
                <th>الاسم</th>
                <th>الرقم القومي</th>
                <th>النوع</th>
                <th>الرقم الطبي</th>
                <th>الهاتف</th>
                <th>العنوان</th>
                <th>الإجراءات</th>
            </tr>
            </thead>
            <tbody id="patientList">
            @foreach($patients->items() as $patient)
                <tr data-id="<%= patient.id %>">
                    <td>{{ $patient->id }}</td>
                    <td>{{ $patient->name }}</td>
                    <td>{{ $patient->national_number }}</td>
                    <td>{{ $patient->gender }}</td>
                    <td>{{ $patient->medical_number }}</td>
                    <td>{{ $patient->phone }}</td>
                    <td>{{ $patient->address }}</td>
                    <td>
                        <button class="deletePatientBtn">حذف</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pagination">
            @if ($patients->currentPage() > 1)
            <a href="?page=<%= currentPage - 1 %>">السابق</a>
            @endif
            @for($i = 1; $i <= $patients->total(); $i++)
            <a href="?page=<{{ $i }} >" class="{{$patients->currentPage() === $i ? 'active' : '' }}">{{ $i }}</a>
            @endfor
            @if($patients->currentPage() < $patients->total())
            <a href="?page=<{{ $patients->currentPage() + 1 }}>">التالي</a>
            @endif
        </div>
    </div>

<script>
    // Get modal elements
    const modal = document.getElementById("checkInPatientModal");
    const btn = document.getElementById("checkInPatientBtn");
    const span = document.getElementById("closeModal");

    // Open the modal
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // Close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // Close modal if user clicks outside of it
    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }

    // Handle patient check-in form submission
    document.getElementById('checkInPatientForm').addEventListener('submit', async function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch('/patients', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            console.log(response);

            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'تم التسجيل بنجاح',
                    confirmButtonText: 'حسناً'
                }).then(() => {
                    location.reload();
                });
            } else {
                const errorData = await response.json();
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: errorData.message,
                    confirmButtonText: 'حسناً'
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'حدث خطأ أثناء التسجيل. حاول مرة أخرى.',
                confirmButtonText: 'حسناً'
            });
        }
    });

    // Handle patient deletion
    document.querySelectorAll('.deletePatientBtn').forEach(button => {
        button.addEventListener('click', async function() {
            const patientId = this.closest('tr').dataset.id;

            if (confirm('هل أنت متأكد من رغبتك في حذف هذا المريض؟')) {
                try {
                    const response = await fetch(`/patients/${patientId}`, {
                        method: 'DELETE'
                    });

                    if (response.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الحذف بنجاح',
                            confirmButtonText: 'حسناً'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        const errorData = await response.json();
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: errorData.message,
                            confirmButtonText: 'حسناً'
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'حدث خطأ أثناء الحذف. حاول مرة أخرى.',
                        confirmButtonText: 'حسناً'
                    });
                }
            }
        });
    });
</script>
@endsection
