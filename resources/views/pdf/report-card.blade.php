<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Official Academic Report Card - {{ $student->name ?? 'Sokha Chan' }}</title>
    <style>
        @page {
            margin: 25px 30px;
            size: A4 portrait;
        }

        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.4;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .school-title {
            font-size: 18px;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .school-subtitle {
            font-size: 10px;
            color: #64748b;
            margin-top: 3px;
        }

        .doc-badge {
            display: inline-block;
            background-color: #eff6ff;
            color: #1d4ed8;
            font-weight: bold;
            font-size: 9px;
            padding: 4px 10px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 1px solid #bfdbfe;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }

        .info-table td {
            padding: 7px 12px;
            font-size: 10px;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-label {
            color: #64748b;
            font-weight: bold;
            width: 20%;
            text-transform: uppercase;
            font-size: 9px;
        }

        .info-value {
            color: #0f172a;
            font-weight: 600;
            width: 30%;
        }

        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .grades-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 10px;
            text-align: left;
            border: 1px solid #1e3a8a;
        }

        .grades-table td {
            padding: 8px 10px;
            font-size: 10px;
            border: 1px solid #e2e8f0;
        }

        .grades-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: monospace; }
        .font-bold { font-weight: bold; }

        .grade-pill {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 9px;
        }

        .grade-a { background-color: #dcfce7; color: #15803d; }
        .grade-b { background-color: #e0f2fe; color: #0369a1; }
        .grade-c { background-color: #fef3c7; color: #b45309; }

        .summary-box {
            width: 100%;
            margin-bottom: 20px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-cell {
            padding: 10px;
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            text-align: center;
        }

        .scale-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            color: #64748b;
            margin-bottom: 25px;
            border: 1px solid #e2e8f0;
        }

        .scale-table td {
            padding: 4px 6px;
            border: 1px solid #e2e8f0;
            text-align: center;
        }

        .signature-table {
            width: 100%;
            margin-top: 35px;
        }

        .signature-line {
            width: 80%;
            border-top: 1px solid #64748b;
            margin: 45px auto 5px auto;
        }

        .signature-title {
            font-size: 9px;
            color: #475569;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

@php
    $studentName = isset($student) && isset($student->user) ? $student->user->name : (isset($student->name) ? $student->name : 'Sokha Chan');
    $studentCode = isset($student->student_code) ? $student->student_code : 'STU-1001';
    $classroomName = isset($classroom->name) ? $classroom->name : 'Grade 10-A (Room 302)';
    $gradeLevel = isset($classroom->grade_level) ? $classroom->grade_level : 'Grade 10';
    $dob = isset($student->date_of_birth) && is_object($student->date_of_birth) ? $student->date_of_birth->format('M d, Y') : 'May 15, 2008';
    $gender = isset($student->gender) ? $student->gender : 'Male';
    $parentName = isset($student->parent_name) ? $student->parent_name : 'Chan Dara';
    $parentPhone = isset($student->parent_phone) ? $student->parent_phone : '+855 12 345 678';
@endphp

    <!-- Official Header -->
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 70%; vertical-align: top;">
                <p class="school-title">SETEC INSTITUTE</p>
                <p class="school-subtitle">FACULTY OF COMPUTER SCIENCE & WEB ENGINEERING</p>
                <p style="font-size: 9px; color: #94a3b8; margin: 2px 0 0 0;">Phnom Penh, Cambodia &bull; Tel: (855) 23 882 110 &bull; www.setec.edu.kh</p>
            </td>
            <td style="width: 30%; text-align: right; vertical-align: top;">
                <div class="doc-badge">Academic Transcript</div>
                <p style="font-size: 9px; color: #64748b; margin: 6px 0 0 0;">Date Issued: <strong>{{ date('M d, Y') }}</strong></p>
                <p style="font-size: 9px; color: #64748b; margin: 2px 0 0 0;">Academic Year: <strong>2025-2026</strong></p>
            </td>
        </tr>
    </table>

    <!-- Student Details Grid -->
    <table class="info-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="info-label">Student Name:</td>
            <td class="info-value">{{ $studentName }}</td>
            <td class="info-label">Student ID:</td>
            <td class="info-value font-mono">{{ $studentCode }}</td>
        </tr>
        <tr>
            <td class="info-label">Classroom / Room:</td>
            <td class="info-value">{{ $classroomName }}</td>
            <td class="info-label">Grade Level:</td>
            <td class="info-value">{{ $gradeLevel }}</td>
        </tr>
        <tr>
            <td class="info-label">Date of Birth:</td>
            <td class="info-value">{{ $dob }}</td>
            <td class="info-label">Gender:</td>
            <td class="info-value" style="text-transform: capitalize;">{{ $gender }}</td>
        </tr>
        <tr>
            <td class="info-label">Guardian / Parent:</td>
            <td class="info-value">{{ $parentName }}</td>
            <td class="info-label">Emergency Phone:</td>
            <td class="info-value font-mono">{{ $parentPhone }}</td>
        </tr>
    </table>

    <!-- Subject Marks Breakdown -->
    <table class="grades-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 15%;">Subject Code</th>
                <th style="width: 35%;">Subject Title</th>
                <th style="width: 15%; text-align: center;">Score (100)</th>
                <th style="width: 15%; text-align: center;">Letter Grade</th>
                <th style="width: 15%; text-align: center;">GPA Points</th>
            </tr>
        </thead>
        <tbody>
            @php
                $gradesData = [
                    ['code' => 'WEB401', 'title' => 'Web Application Development (Laravel)', 'score' => '96.50', 'grade' => 'A', 'point' => '4.00', 'class' => 'grade-a'],
                    ['code' => 'DBS301', 'title' => 'Relational Database Management Systems', 'score' => '91.00', 'grade' => 'A', 'point' => '4.00', 'class' => 'grade-a'],
                    ['code' => 'MATH101', 'title' => 'Discrete Mathematics & Logic', 'score' => '88.50', 'grade' => 'B+', 'point' => '3.50', 'class' => 'grade-b'],
                    ['code' => 'ENG201', 'title' => 'Technical English Communications', 'score' => '93.00', 'grade' => 'A', 'point' => '4.00', 'class' => 'grade-a'],
                    ['code' => 'NET202', 'title' => 'Data Communication & Computer Networks', 'score' => '85.00', 'grade' => 'B+', 'point' => '3.50', 'class' => 'grade-b'],
                    ['code' => 'PRG102', 'title' => 'Object-Oriented Programming (OOP)', 'score' => '89.00', 'grade' => 'B+', 'point' => '3.50', 'class' => 'grade-b'],
                ];
            @endphp

            @foreach($gradesData as $index => $item)
                <tr>
                    <td class="text-center font-mono">{{ $index + 1 }}</td>
                    <td class="font-mono font-bold" style="color: #1e3a8a;">{{ $item['code'] }}</td>
                    <td>{{ $item['title'] }}</td>
                    <td class="text-center font-mono font-bold">{{ $item['score'] }}</td>
                    <td class="text-center">
                        <span class="grade-pill {{ $item['class'] }}">{{ $item['grade'] }}</span>
                    </td>
                    <td class="text-center font-mono font-bold">{{ $item['point'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Academic Summary KPI Box -->
    <table class="summary-table" cellpadding="0" cellspacing="0" style="margin-bottom: 16px;">
        <tr>
            <td class="summary-cell" style="width: 25%;">
                <span style="font-size: 9px; color: #64748b; text-transform: uppercase;">Total Score</span>
                <p style="font-size: 16px; font-weight: bold; margin: 4px 0 0 0; color: #0f172a;" class="font-mono">543.00 / 600</p>
            </td>
            <td class="summary-cell" style="width: 25%;">
                <span style="font-size: 9px; color: #64748b; text-transform: uppercase;">Semester GPA</span>
                <p style="font-size: 16px; font-weight: bold; margin: 4px 0 0 0; color: #1e3a8a;" class="font-mono">3.75 / 4.00</p>
            </td>
            <td class="summary-cell" style="width: 25%;">
                <span style="font-size: 9px; color: #64748b; text-transform: uppercase;">Academic Standing</span>
                <p style="font-size: 14px; font-weight: bold; margin: 4px 0 0 0; color: #15803d;">HONORS / PASSED</p>
            </td>
            <td class="summary-cell" style="width: 25%;">
                <span style="font-size: 9px; color: #64748b; text-transform: uppercase;">Attendance Record</span>
                <p style="font-size: 16px; font-weight: bold; margin: 4px 0 0 0; color: #0f172a;" class="font-mono">98.2%</p>
            </td>
        </tr>
    </table>

    <!-- Grading Scale Legend -->
    <table class="scale-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="font-weight: bold; background-color: #f1f5f9; width: 15%;">GRADING SCALE</td>
            <td><strong>A:</strong> 90-100 (4.0)</td>
            <td><strong>B+:</strong> 85-89 (3.5)</td>
            <td><strong>B:</strong> 80-84 (3.0)</td>
            <td><strong>C+:</strong> 75-79 (2.5)</td>
            <td><strong>C:</strong> 65-74 (2.0)</td>
            <td><strong>D:</strong> 50-64 (1.0)</td>
            <td><strong>F:</strong> &lt; 50 (0.0)</td>
        </tr>
    </table>

    <!-- Signatures and Verification -->
    <table class="signature-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 33%; text-align: center; vertical-align: bottom;">
                <div class="signature-line"></div>
                <p class="signature-title">Class Head Teacher</p>
                <p style="font-size: 8px; color: #94a3b8; margin: 2px 0 0 0;">Prof. Virak Meas</p>
            </td>
            <td style="width: 34%; text-align: center; vertical-align: bottom;">
                <div style="width: 60px; height: 60px; border: 2px dashed #cbd5e1; border-radius: 50%; margin: 0 auto 5px auto; line-height: 60px; font-size: 8px; color: #94a3b8;">
                    OFFICIAL SEAL
                </div>
                <p style="font-size: 8px; color: #94a3b8;">Registrar Office Verification</p>
            </td>
            <td style="width: 33%; text-align: center; vertical-align: bottom;">
                <div class="signature-line"></div>
                <p class="signature-title">Academic Director / Dean</p>
                <p style="font-size: 8px; color: #94a3b8; margin: 2px 0 0 0;">Dean of Computer Science</p>
            </td>
        </tr>
    </table>

</body>
</html>
