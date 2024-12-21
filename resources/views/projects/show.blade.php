<!-- داخل ملف Blade (مثلاً resources/views/project/show.blade.php) -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Approval</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Project Details</h1>

    <!-- عرض بيانات سلسلة الموافقات -->
    <ul id="approval-chain-list"></ul>

    <!-- زر الموافقة -->
    <button id="approve-btn" style="display: none;">Approve</button>

    <script>
        $(document).ready(function() {
            // تحديد الـ project_id من الـ URL أو البيانات الموجودة في الصفحة
            var projectId = @json($project->id);

            // جلب بيانات سلسلة الموافقات من API
            $.ajax({
                url: '/api/projects/' + projectId + '/approval-chain',
                method: 'GET',
                success: function(response) {
                    var approvalChain = response.approval_chain;
                    var approvalList = $('#approval-chain-list');
                    var approveBtn = $('#approve-btn');

                    // عرض سلسلة الموافقات
                    approvalChain.forEach(function(approver, index) {
                        var status = approver.approved ? 'Approved' : 'Pending';
                        approvalList.append('<li>User ' + approver.name + ' - ' + status + '</li>');

                        // التحقق إذا كان المستخدم هو التالي في السلسلة لعرض زر الموافقة
                        if (!approver.approved && approver.id === response.current_user.id) {
                            approveBtn.show();
                        }
                    });
                }
            });

            // الموافقة على المشروع
            $('#approve-btn').click(function() {
                $.ajax({
                    url: '/api/projects/' + projectId + '/approve',
                    method: 'POST',
                    success: function(response) {
                        alert(response.message);
                        location.reload();  // إعادة تحميل الصفحة لتحديث السلسلة
                    },
                    error: function(response) {
                        alert(response.responseJSON.message);
                    }
                });
            });
        });
    </script>
</body>
</html>
