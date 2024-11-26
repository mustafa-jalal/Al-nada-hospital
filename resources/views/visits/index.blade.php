@extends('layouts.app')

@section('content')
<div class="card">
    <h2>جميع الزيارات</h2>
    <table class="patient-table">
        <thead>
            <tr>
                <th>الاسم</th>
                <th>نوع الزيارة</th>
                <th>تسجيل الدخول</th>
                <th>تسجيل الخروج</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($visits as $visit)
                <tr>
                    <td>{{ $visit->patient->name }}</td>
                    <td>{{ $visit->visit_type }}</td>
                    <td>{{ $visit->checked_at }}</td>
                    <td>{{ $visit->checked_out_at ?? 'لم يتم الخروج' }}</td>
                    <td>
                        @if(!$visit->checked_out_at)
                            <button class="checkoutBtn" data-id="{{ $visit->id }}">تسجيل الخروج</button>
                        @endif
                        <!-- <button class="cancelBtn" data-id="{{ $visit->id }}">إلغاء</button> -->
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination">
        {{ $visits->links() }}
    </div>
</div>

<script>
    document.querySelectorAll('.checkoutBtn').forEach(button => {
        button.addEventListener('click', async function() {
            const visitId = this.dataset.id;
            if (confirm('هل أنت متأكد من رغبتك في تسجيل خروج هذا المريض؟')) {
                try {
                    const response = await fetch(`/visits/${visitId}/checkout`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    if (response.ok) {
                        alert('تم تسجيل خروج الزيارة بنجاح.');
                        location.reload();
                    }
                } catch (error) {
                    alert('حدث خطأ أثناء تسجيل الخروج.');
                }
            }
        });
    });

    document.querySelectorAll('.cancelBtn').forEach(button => {
        button.addEventListener('click', async function() {
            const visitId = this.dataset.id;
            if (confirm('هل أنت متأكد من رغبتك في إلغاء هذه الزيارة؟')) {
                try {
                    const response = await fetch(`/visits/${visitId}/cancel`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    if (response.ok) {
                        alert('تم إلغاء الزيارة بنجاح.');
                        location.reload();
                    }
                } catch (error) {
                    alert('حدث خطأ أثناء إلغاء الزيارة.');
                }
            }
        });
    });
</script>
@endsection