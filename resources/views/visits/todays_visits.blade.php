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
                        text: "{{ $error }}",
                        confirmButtonText: 'حسناً'
                    });
                });
            </script>
        @endif

        <button id="checkInPatientBtn">تسجيل زيارة جديدة</button>

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
                    <div class="input-group">
                        <select name="gender" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; font-family: 'Cairo', sans-serif; background-color: white;">
                            <option value="" disabled selected>اختر النوع</option>
                            <option value="male">ذكر</option>
                            <option value="female">أنثى</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <select name="visit_type" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; font-family: 'Cairo', sans-serif; background-color: white;">
                            <option value="" disabled selected>اختر نوع الزيارة</option>
                            <option value="checkup">عيادة</option>
                            <option value="surgery">عملية جراحية</option>
                            <option value="emergency">طوارئ</option>
                        </select>
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <button type="submit">تسجيل الدخول</button>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>زيارات اليوم</h2>
        <div class="filters">
            <select id="checkoutFilter" class="modern-select">
                <option value="all">الكل</option>
                <option value="checked_in">لم يتم الخروج</option>
                <option value="checked_out">تم الخروج</option>
            </select>
            <select id="genderFilter" class="modern-select">
                <option value="all">الكل</option>
                <option value="male">ذكر</option>
                <option value="female">أنثى</option>
            </select>
            <input type="text" id="searchInput" class="modern-input" placeholder="بحث...">
        </div>
        <style>
            .filters {
                display: flex;
                justify-content: space-between;
                margin-bottom: 20px;
            }
            .modern-select, .modern-input {
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                font-size: 14px;
                width: 30%;
            }
            .modern-select {
                background-color: white;
                appearance: none;
                background-image: url("data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 10px center;
                padding-right: 30px;
            }
            .modern-input {
                transition: border-color 0.3s ease;
            }
            .modern-input:focus {
                outline: none;
                border-color: #4CAF50;
            }
        </style>
        <table class="patient-table">
            <thead>
            <tr>
                <th>الاسم</th>
                <th>النوع</th>
                <th>الرقم الطبي</th>
                <th>القسم</th>
                <th>تاريخ الزيارة</th>
                <th>تاريخ الخروج</th>
                <th>الإجراءات</th>
            </tr>
            </thead>
            <tbody id="patientList">
            @foreach($visits->items() as $visit)
                <tr data-id="{{ $visit->id }}" data-gender="{{ $visit->patient->gender }}" data-checkout="{{ $visit->checked_out_at ? 'checked_out' : 'checked_in' }}">
                    <td>{{ $visit->patient->name }}</td>
                    <td>{{ $visit->patient->gender }}</td> 
                    <td>{{ $visit->patient->medical_number }}</td>
                    <td>{{ $visit->visit_type }}</td>
                    <td>{{ $visit->checked_at }}</td>
                    <td>{{ $visit->checked_out_at ?? "لم يتم الخروج بعد" }}</td>
                    <td>
                        <button onclick="printSticker({{ $visit->id }})" class="printStickerBtn" style="background-color: #4CAF50; margin-right: 2px; padding: 0.15rem 0.3rem; font-size: 0.7rem; display: inline-block;"><i class="fas fa-print"></i></button>
                        <button class="checkOutBtn" style="background-color: #f44336; padding: 0.15rem 0.3rem; font-size: 0.7rem; display: inline-block;"><i class="fas fa-sign-out-alt"></i></button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pagination">
            @if ($visits->currentPage() > 1)
                <a href="?page={{ $visits->currentPage() - 1 }}">السابق</a>
            @endif
            @for($i = 1; $i <= round($visits->total()/10, 0); $i++)
                <a href="?page={{ $i }}" class="{{ $visits->currentPage() == $i ? 'active' : '' }}"> {{ $i }} </a>
            @endfor
            @if($visits->currentPage() != $visits->lastPage())
                <a href="?page={{ $visits->currentPage() + 1 }}">التالي</a>
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
                const response = await fetch('/visits', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value // CSRF token
                    },
                    body: JSON.stringify(data)
                });

                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم التسجيل بنجاح',
                        confirmButtonText: 'حسناً'
                    }).then(() => {
                        modal.style.display = "none"; // Close the modal
                        location.reload(); // Refresh the page
                    });
                } else {
                    const errorResponse = await response.json();
                    let errorMessage = 'حدث خطأ غير معروف.';

                    if (errorResponse.errors) {
                        const firstErrorKey = Object.keys(errorResponse.errors)[0];
                        if (firstErrorKey && errorResponse.errors[firstErrorKey].length > 0) {
                            errorMessage = errorResponse.errors[firstErrorKey][0];
                        }
                    } else if(errorResponse.message) {
                        errorMessage = errorResponse.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: errorMessage,
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
        document.querySelectorAll('.checkoutBtn').forEach(button => {
            button.addEventListener('click', async function() {
                const visitId = this.closest('tr').dataset.id;

                if (confirm('هل أنت متأكد من رغبتك تسجيل خروج هذا المريض؟')) {
                    try {
                        const response = await fetch(`/visits/${visitId}/checkout`, {
                            method: 'POST'
                        });

                        if (response.ok) {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم تسجيل خروج الزيارة بنجاح',
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
                            text: 'حدث خطأ أثناء تسجيل خروج المريض. حاول مرة أخرى.',
                            confirmButtonText: 'حسناً'
                        });
                    }
                }
            });
        });

    function printSticker(visitId) {
        // Open the print view in a new tab
        const printWindow = window.open(`/visits/${visitId}/sticker`, '_blank');
        
        // Optional: Automatically close the tab after printing
        printWindow.onload = function() {
            printWindow.print();
            printWindow.onafterprint = function() {
                printWindow.close();
            };
        };
    }
    </script>
@endsection
