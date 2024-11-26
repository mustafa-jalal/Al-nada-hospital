<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>Patient Card</title>
  <style>
    /* Set the page size for thermal printing */
    @page {
      size: 38mm 25mm;
      margin: 0px;
      padding-top: 10%
    }

    /* Style for the printable patient card */
    #printable {
      width: 38mm;
      height: 25mm;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: flex-start;
      padding: 0px; /* Optional padding */
      font-size: 10px;
      font-family: Arial, sans-serif;
      box-sizing: border-box;
      direction: rtl; /* Right-to-left direction for Arabic */
    }

    /* Print-only styles */
    @media print {
      body * {
        visibility: hidden;
      }
      #printable, #printable * {
        visibility: visible;
      }
      #printable {
        position: absolute;
        top: 0;
        left: 0;
	font-size: 9px;
      }
    }

    /* Styling for table elements */
    #printable h3 {
      margin: 0;
      font-size: 9px;
      font-weight: bold;
      text-align: center;
      width: 100%;
    }

    #printable table {
      width: 100%;
      border-collapse: collapse;
    }

    #printable table td {
      padding: 2px 0;
      vertical-align: top;
    }

    #printable table td:first-child {
      font-weight: bold;
      width: 40%; /* Adjust the width as needed */
    }
  </style>
</head>
<body>

  <!-- The patient card content -->
  <div id="printable">
    <h3> مستشفى الندى التخصصى</h3>
    <table>
      <tr>
        <td>الاسم:</td>
        <td>{{ $visit->patient->name }}</td>
      </tr>
      <tr>
        <td>النوع:</td>
        <td>{{ $visit->patient->gender }}</td>
      </tr>
      <tr>
        <td>الرقم الطبى:</td>
        <td>{{ $visit->patient->medical_number }}</td>
      </tr>
      <tr>
        <td>الرقم القومى:</td>
        <td>{{ $visit->patient->national_number }}</td>
      </tr>
      <tr>
        <td>القسم:</td>
        <td>{{ $visit->visit_type }}</td>
      </tr>
      <tr>
        <td>تاريخ الدخول:</td>
        <td>{{ $visit->checked_at }}</td>
      </tr>
    </table>
  </div>

  <script>
    // Automatically trigger print when the page loads
    window.onload = function() {
        window.print();
    }

    // Listen for the 'afterprint' event, which occurs when the print dialog is closed
    window.onafterprint = function() {
      window.close(); // Close the window after the print dialog is closed
    }
  </script>

</body>
</html>
