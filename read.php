<?php
session_start();
// Check if the user is already logged in, if yes then redirect him to welcome page
if( $_SESSION["loggedin"] == false){
    header("location: login.php");
    exit;
}
if (isset($_GET['contract_id']) && $_GET['contract_id']!="") {
	$contract_id = $_GET['contract_id'];
}
else{
    header("location: dashboard.php");
    exit;
} 
require_once 'config/config.php';

require_once('tcpdf/tcpdf.php');


$sql = "SELECT *, contract.Job_Position from employee inner join contract on contract.Contract_ID = employee.Contract_ID  where employee.Contract_ID = ".$contract_id;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

    } else {
      $errorMsg = "Invalid Credentials";
    }
    $conn->close();

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('HRSystem');
$pdf->SetTitle('New Contract');
$pdf->SetSubject('Contract Generation');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 018', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language dependent data:
$lg = Array();
$lg['a_meta_charset'] = 'UTF-8';
$lg['a_meta_dir'] = 'rtl';
$lg['a_meta_language'] = 'fa';
$lg['w_page'] = 'page';

// set some language-dependent strings (optional)
$pdf->setLanguageArray($lg);

// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '', 12);

// add a page
$pdf->AddPage();

$timestamp = strtotime($row['Gregorian_Date']);

$contractDay = date('l', $timestamp);
$contractDate = date('Y-m-d');

// Arabic and English content
$htmlcontent1 = '<h1 class="title" style="text-align: center;">عقد عمل موظف سعودي</h1>
<p>تم إبرام هذا العقد يوم   '.$contractDay.' '.$contractDate.' ميلادي بين كل من: </p> <p>ابتداءً من
:'.$row["Start_Date"].'</p><p>الى:'.$row["End_Date"].'</p>
<table>
    <tbody>
        <tr>
            <td style="width: 20%">
            الطرف الأول:
            </td>
            <td style="width: 80%">الجمعية السعودية لاضطراب فرط الحركة وتشتت الانتباه المسجلة بوزارة العمل والتنمية الاجتماعية بترخيص رقم ٤٧٤ وعنوانها ص.ب. ٩٤٠٣٧ الرمز البريدي ١١٦٩٣ الرياض، ويمثلها في هذا العقد المدير التنفيذي الأستاذة نوال بنت محمد الشريف. </td>
        </tr>
        <tr>
            <td style="width: 20%">
            الطرف الثاني:
            </td>
            <td style="width: 80%">اسم الموظف	'.$row["First_Name"].' '.$row["Middle_Name"].' '.$row["Last_Name"].'	الجنس '.$row["Gender"].'	الجنسية	'.$row["Nationality"].'
            بموجب بطاقة الهوية الوطنية رقم	'.$row["National_ID"].'	مصدرها	'.$row["City"].'
            وعنوانه	'.$row["Street"].' '.$row["Neighborhood"].' هاتف رقم	'.$row["Phone Number"].'
            </td>
        </tr>
    </tbody>
</table>
<p>تمهيد<br/>الجمعية السعودية لاضطراب فرط الحركة وتشتت الانتباه أول جمعية غير ربحية متخصصة في السعودية تأسست لخدمة ذوي افتا وذلك من خلال رفع الوعي بالاضطراب، وتدريب الأهالي والتربويين، وتقديم المشورة للجهات ذات الصلة، وتوفير الدعم اللوجستي للأشخاص المصابين بالإضافة إلى الدعم المادي للذين لا يملكون القدرة المالية للحصول على التشخيص والعلاج.<br/>حيث اتفق الطرفان وتراضيا، بعد أن أقرا بأهليتهما الكاملة المعتبرة شرعاً ونظاماً للتعاقد على ما يلي: </p>

<p>البند الأول:<br/>يعتبر التمهيد السابق جزءاً لا يتجزأ من هذا العقد.</p>

<p>البند الثاني: الوظيفة ومكان العمل:</p>

<ul>
    <li>‌يقــر الطـــرف الثــاني أنـه بنـاء علــى طلبـــه ومـــوافقة الطــــرف الأول قبــل العمــل لــدى الطـــرف الأول وتحــت إدارتــه وإشـرافـــه بوظيفة	'.$row["Job_Position"].'	في مدينة	الرياض	أو بأي جهة أخرى يمارس فيها الطرف الأول نشاطه داخل المملكة وأن يقوم بكافة</li>
    <li>ما يدخل في اختصاص هذه الوظيفة مستخدماً في ذلك كفاءته ومؤهلاته وخبراته.
‌ب.	لا يجوز للطرف الأول تكليف الموظف بعمل يختلف اختلافاً جوهرياً عن مهام الوظيفة المتفق عليها في هذا العقد بغير موافقته الكتابية، إلا في حالات الضرورة التي قد تقتضيها ظروف عارضة ولمدة لا تتجاوز ثلاثين يوماً في السنة وفقاً للمادة رقم (60) من نظام العمل. </li>
    <li>‌ج.	لا يجوز للطرف الأول نقل الموظف من مكان عمله الأصلي إلى مكان آخر يقتضي تغيير محل إقامته إذا كان من شأن النقل أن يلحق بالموظف ضرراً جسيماً ولم يكن له سبب مشروع تقتضيه طبيعة العمل وفقاَ للمادة رقم (58) من نظام العمل، كما يقر الطرف الثاني كذلك بأنه مستعد للقيام بأي عمل يسنده إليه الطرف الأول، يكون متفقاً ومؤهلاته العلمية أو خبرته العملية.</li>
</ul>

<p>البند الثالث: مدة العقد وفترة التجربة:</p>
<ul>
    <li>أبرم هذا العقد لمدة سنة تتجدد تلقائيا لفترات مماثلة ما لم يبد أحد الطرفين عدم رغبته من تـاريخ مبـاشـرته للعمـل وتم العمل عليه ابتداءً من تاريخ إبرام العقد.</li>
    <li>يخضع الطرف الثاني لفترة تجربة مدتها ثلاثة أشهر ابتداءً من تاريخ بداية العقد الموضحة في الفقرة (أ) أعلاه ولا تدخل في حساب فترة التجربة إجازة عيدي الفطر والأضحى والإجازة المرضية، ويجوز للطرفين إنهاء هذا العقد خلال هذه الفترة وفقاً للمادة رقم (53) من نظام العمل.</li>
    <li>لا يجوز وضع الموظف تحت التجربة أكثر من مرة واحدة لدى الطرف الأول، واستثناء من ذلك يجوز باتفاق طرفي العقد إخضاع الموظف لفترة تجربة ثانية لا تتجاوز مدتها تسعين يوماً، وإذا انتهى العقد خلال فترة التجربة فإن أياً من الطرفين لا يستحق تعويضاً كما لا يستحق الموظف مكافأة نهاية الخدمة وفقاً للمادة رقم (54) من نظام العمل. </li>
</ul>

<p>البند الرابع: واجبات طرفي العقد:</p>

<p>‌أ.	واجبات الطرف الأول: يجب على الطرف الأول ما يلي:<br/> 
-	أن يعامل الموظف بالاحترام اللائق (المادة رقم 61 من نظام العمل). <br/>
-	أن يمتنع عن كل قول أو فعل يمس كرامة الموظف ودينه (المادة رقم 61 من نظام العمل). <br/>
-	ألا يحتجز أجر الموظف أو جزء منه دون سند قضائي موجب لذلك. <br/>
-	ألا يحسم أي مبلغ من أجر الموظف إلا في الحالات المنصوص عليها في المادة رقم (92،93) من نظام العمل.<br/>
-	أن يعطى الموظف وبناء على طلبه شهادة خدمة عند انتهاء خدمته لديه، وأن يعيد للموظف جميع ما أودعه لديه وفقاً للمادة رقم (64) من نظام العمل. <br/>
-	عدم توقيع جزاء تأديبي على الموظف إلا بعد إبلاغه كتابة بما نسب إليه واستجوابه، وأن يكون الجزاء منصوص عليه في نظام العمل أو في لائحة تنظيم العمل وفقاً للمواد أرقام (66، 67، 68، 69، 70، 71، 72) من نظام العمل.<br/>
-	أن يطلع الموظف على لوائح تنظيم العمل لديه وفقاً للمادة رقم (13) من نظام العمل. <br/><br/>
‌ب.	واجبات الطرف الثاني:<br/>
-	أن ينجز العمل وفقاً لأصول المهنة ووفق تعليمات الطرف الأول، إذا لم يكن في هذه التعليمات ما يخالف العقد أو النظام أو الآداب العامة، ولم يكن في تنفيذها ما يعرض للخطر. <br/>
-	أن يعتني عناية كافية بالآلات والأدوات والمهمات والخامات المملوكة للطرف الأول الموضوعة تحت تصرفه، أو التي تكون في عهدته، وأن يعيد إلى الطرف الأول المواد غير المستهلكة. <br/>
-	أن يلتزم حسن السلوك والأخلاق أثناء العمل. <br/>
-	أن يقدم كل عون ومساعدة دون أن يشترط لذلك أجراً إضافياً في حالات الكوارث والأخطار التي تهدد سلامة مكان العمل أو الأشخاص العاملين فيه. <br/>
-	أن يخضع – وفقاً لطلب الطرف الأول – للفحوص الطبية التي يرغب الطرف الأول في إجرائها عليه قبل الالتحاق بالعمل أو أثناءه للتحقق من خلوه من الأمراض المهنية أو السارية. <br/>
-	أن يحفظ الأسرار الفنية والتجارية والصناعية للمواد التي ينتجها، أو التي أسهم في إنتاجها بصورة مباشرة أو غير مباشرة، وجميع الأسرار المهنية المتعلقة بالعمل أو المنشأة التي من شأن إفشائها الإضرار بمصلحة الطرف الأول. <br/>
-	القيام بأي مهام يكلف بها حسب ما تقتضي مصلحة العمل.<br/>
</p>

<p>
يستحق الطرف الثاني الراتب والبدلات والمزايا التالية: <br/>
‌أ.	يستحق الطرف الثاني راتباً أساسياً مقـــــــــــــــــداره ('.$row["Salary"].') ريال، وبدل سكن شهري مقداره ('.$row["Housing_Allowance"].') ريال، وبدل نقل مقداره ('.$row["Transportation_Allowance"].') ريال. ويلتزم الطرف الأول بتسليم راتب الموظف كاملاً عند نهاية كلّ شهر ميلادي.<br/>
‌ب.	يوفر الطرف الأول الرعاية الصحية للطرف الثاني وأسرته بالطريقة التي يراها الطرف الأول وفقاً للمواد (142، 143، 144) من نظام العمل.<br/>
‌ج.	يلتزم الطرف الأول بتسجيل الطرف الثاني في نظام التأمينات الاجتماعية منذ بداية سريان هذا العقد الموضحة في الفقرة ( أ ) من البند الثاني من هذا العقد. <br/><br/>
البند السادس: ساعات العمل والراحة الأسبوعية:<br/>
(‌أ)	يلتزم الطرف الثاني بأن يعمل لدى الطرف الأول ولمدة ثماني ساعات عمل يومياً ولا تزيد عن ثماني وأربعين ساعة أسبوعياً، وتخفض إلى ست عمل ساعات في شهر رمضان المبارك بحيث لا تزيد عن ست وثلاثين ساعة أسبوعياً وفقاً للمادة (98) من نظام العمل موزعة حسب ما تقتضيه مصلحة العمل مع مراعاة ما تقتضي من المواد أرقام (100،  101،  102،  103،  106،  108)  من نظام العمل. <br/>
(‌ب)	يثبت الطرف الثاني حضوره وانصرافه حسب الطريقة التي يحددها الطرف الأول لمتابعة ساعات الدوام وكذلك عليه الامتثال للتفتيش متى طلب منه ذلك. <br/>
(‌ج)	في حالة تكليف الموظف بالعمل خارج أوقات العمل الرسمي يستحق أجراً إضافياً عن ساعات العمل الإضافية يوازي أجر الساعة مضافاً إليها مقداره 50% من أجره الأساسي مع مراعاة ما تقتضيه الفقرة (1) من المادة رقم (107) من نظام العمل. <br/>
(‌د)	يحق للموظف الحصول على راحة (عطلة) أسبوعية لا تقل عن أربع وعشرين ساعة متتالية وبأجر كامل ولا يجوز تعويضه بمقابل.<br/><br/>

البند السابع: الإجازات:<br/>
(‌أ)	يحق للطرف الثاني إجازة سنوية عن كل عام قدرها 30 يوماً وتكون الإجازة بأجر يدفع مقدماً،  ويجب أن يتمتع الموظف بها في سنة استحقاقها،  ولا يجوز التنازل عنها أو أن يتقاضى بدلاً نقدياً عوضاً عن الحصول عليها أثناء خدمته،  ويتولى الطرف الأول تحديد تاريخ بدايتها وفق ما تسمح به ظروف العمل، وللطرف الأول تأجيل إجازة الموظف بعد نهاية سنة الاستحقاق أذا اقتضت ظروف العمل ذلك لمدة لا تزيد عن تسعين يوماً،  فإذا اقتضت ظروف العمل استمرار التأجيل وجب على الحصول على موافقة الموظف كتابة على ألا يتعدى التأجيل نهاية السنة التالية لسنة استحقاق الإجازة مع مراعاة أحكام المواد أرقام (111،110،109)  من نظام العمل. 
(‌ب)	للموظف التمتع بإجازة بأجر كامل في عيدي الفطر والأضحى كما يحددها قرار وزير العمل واللائحة التنفيذية لنظام العمل.  <br/>
(‌ج)	يستحق الموظف وفقاً للمادة رقم (113) من نظام العمل إجازة بأجر كامل على النحو التالي: <br/><br/>
- في حالة زواجه خمسة أيام.<br/>
- في حالة وفاة زوجه أو أحد فروعه أو أصوله خمسة أيام. <br/>
- في حالة ولادة مولود له ثلاثة أيام. <br/>
- في حالة وفاة زوج المرأة المسلمة العاملة يستحق الطرف الثاني (إذا كان امرأة) إجازة بأجر كامل مدة لا تقل عن مدة العدة وهي (اربعة أشهر وعشرة أيام من تاريخ الوفاة وفقاً لنظام العمل و (15) خمسة عشر يوما للمرأة غير المسلمة.<br/> 
ويحق للطرف الأول أن يطلب الوثائق المؤيدة للحالات المشار إليها. <br/>
(‌د)	يحق للموظف لأداء فريضة الحج الحصول على إجازة لمرة واحدة طوال خدمته لا تقل مدتها عن عشرة أيام ولا تزيد على خمسة عشر يوماً وفقاً للمادة رقم (114) من نظام العمل. <br/>
(‌ه)	يحق للموظف المنتسب إلى مؤسسة تعليمية إجازة بأجر كامل لحضور الامتحانات مع مراعاة ما تقتضيه المادة رقم (115) من           نظام العمل.  <br/>
(‌و)	يحق للموظف إذا ثبت مرضه بموجب تقرير طبي صادر من الجهة المعتمدة لدي الطرف الأول إجازة مرضية بأجر كامل عن الثلاثين يوماً الأولى، وبثلاثة أرباع الأجر عن الستين يوماً التالية ودون أجر للثلاثين يوماً التي تلي ذلك خلال السنة الواحدة سواء أكانت هذه الإجازة متصلة أو متقطعة (يقصد بالسنة الواحدة: السنة التي تبدأ من تاريخ أول إجازة مرضية) وفقاً للمادة رقم (117) من نظام   العمل، وفي جميع الأحوال يجوز للطرف الأول التحقق من صحة التقرير الطبي المقدم من الطرف الثاني. <br/>
(‌ز)	يجوز للطرف الثاني الحصول على إجازة دون أجر يتفق الطرفان على تحديد مدتها، وبعد عقد العمل موقفاً خلال مدة الإجازة فيما زاد على عشرين يوماً، ما لم يتفق الطرفان على خلاف ذلك وفقاً للمادة رقم (116) من نظام العمل. <br/>
(‌ح)	يحق للطرف الثاني – إذا كان امرأة – إجازة وضع لمدة عشرة أسابيع على أن تبدأ قبل تاريخ الولادة المتوقع بأربعة أسابيع بحد أعلى وحسب ما تقتضيه المواد أرقام (156،155،154،153،152،151) من نظام العمل. <br/>
(‌ط)	لا يجوز للموظف أثناء تمتعه بأي من إجازاته المنصوص عليها في هذا العقد أن يعمل لدى منشأة أخرى، فإذا ثبت ذلك فللطرف الأول أن يحرم الموظف من أجره عن مدة الإجازة أو يسترد ما سبق أن أداه إليه من ذلك الأجر وفقاً للمادة رقم (118) من نظام العمل. <br/><br/>

البند الثامن: نقل الطرف الثاني:<br/> 
يحق للطرف الأول في كل وقت – حسبما يراه محققاً لصالح العمل – أن ينقل الطرف الثاني للعمل في أي جهة داخل المملكة العربية السعودية، من الجهات التي يباشر فيها الطرف الأول نشاطه، ويتم نقل الطرف الثاني في هذه الحالة بواسطة وسيلة النقل التي يحددها الطرف الأول، ويتحمل الطرف الأول أية نفقات تترتب على نقل الطرف الثاني مع أمتعته الشخصية، ولا يتحمل الطرف الأول أية نفقات من هذا النوع إذا كان النقل قد تم بناء على طلب الطرف الثاني ولمجرد تلبية طلبه.<br/><br/>

البند التاسع: نهاية الخدمة: <br/>
‌أ.	يحق للطرف الأول فسخ العقد دون مكافأة أو إشعار الموظف أو تعويض في الحالات الواردة في المادة رقم (80) من نظام العمل بشرط أن يتيح للموظف <br/>
الفرصة لكي يبدي أسباب معارضته للفسخ، وهذه الحالات على النحو التالي: <br/>

<br/>إذا وقع من الموظف اعتداء على صاحب العمل أو المدير المسؤول أو أحد رؤسائه أثناء العمل أو بسببه. 
<br/>إذا لم يؤد الموظف التزاماته الجوهرية المترتبة على عقد العمل أو لم يطلع الأوامر المشروعة أو لم يراع عمداً التعليمات – المعلن عنها في مكان ظاهر من قبل الطرف الأول – الخاصة بسلامة العمل والعمال رغم إنذاره كتابة. 
<br/>إذا ثبت اتباعه سلوكاً سيئاً أو ارتكابه عملاً مخلاً بالشرف أو الأمانة. 
<br/>إذا وقع من الموظف عمداً أي فعل أو تقصير يقصد به إلحاق خسارة مادية بالطرف الأول على شرط أن يبلغ الطرف الأول الجهات المختصة بالحادث خلال أربع وعشرين ساعة من وقت علمه بوقوعه. 
<br/>إذا ثبت أن الموظف لجأ إلى التزويد ليحصل على العمل. 
<br/>إذا كان الموظف معيناً تحت الاختبار. 
<br/>إذا تغيب الموظف دون سبب مشروع أكثر من ثلاثين يوماً خلال السنة الواحدة أو أكثر من خمسة عشرة يوم متتالية، على أن يسبق الفصل إنذار كتابي من الطرف الأول للموظف بعد غيابه عشرة أيام في الحالة الأولى وانقطاعه خمسة أيام في الحالة الثانية. 
<br/>إذا ثبت أن استغل مركزه الوظيفي بطريقة غير مشروعة للحصول على نتائج ومكاسب شخصية. 
<br/>إذا ثبت أن الموظف أفشى الأسرار الصناعية أو التجارية الخاصة بالعمل الذي يعمل فيه. 
‌ب.	يحق للطرف الثاني أن يترك العمل دون إشعار، مع احتفاظه بحقوقه النظامية كلها في الحالات الواردة في المادة رقم (81) من نظام<br/><br/>             العمل، وهذه الحالات على النحو التالي:<br/> 
-	إذا لم يفِ الطرف الأول بالتزاماته العقدية أو النظامية الجوهرية إزاء الموظف. <br/>
-	إذا ثبت أن الطرف الأول أو من يمثله قد أدخل عليه الغش وقت التعاقد فيما يتعلق بشروط العمل وظروفه. <br/>
-	إذا كلفه الطرف الأول دون رضاه بعمل يختلف جوهرياً عن العمل المتفق عليه، وخلافاً لما تقرره المادة الستون من هذا النظام.  <br/>
-	إذا وقع من الطرف الأول أو من أحد أفراد أسرته، أو من المدير المسؤول اعتداء يتسم بالعنف، أو سلوك مخل بالآداب نحو الموظف أو أحد أفراد أسرته. <br/>
-	إذا اتسمت معاملة الطرف الأول أو المدير المسؤول بمظاهر من القسوة والجور أو الإهانة. <br/>
-	أذا كان في مقر العمل خطر جسيم يهدد سلامة الموظف أو صحته، بشرط أن يكون الطرف الأول قد علم بوجوده، ولم يتخذ من الإجراءات ما يدل على إزالته. <br/>
-	إذا كان الطرف الأول أو من يمثله قد دفع الموظف بتصرفاته وعلى الأخص بمعاملته الجائرة أو بمخالفته شروط العقد إلى أن يكون الموظف في الظاهر هو الذي أنهى العقد. <br/>
‌ج.	يجوز لأي طرفي العقد إنهائه إذا كان العقد غير محدد المدة، بناء على سبب مشروع يجب بيانه بموجب إشعار يوجه ً إلى الطرف الآخر كتابة قبل الإنهاء بمدة تحدد في العقد، على ألا تقل عن ستين يوما إذا كان أجر العامل يدفع ًّ شهري والا تقل عن ثلاثين يوما بالنسبة إلى غيره وفقاً للمادة رقم (76،75) من نظام العمل. <br/>
‌د.	إذا أنهى العقد لسبب غير مشروع كان للطرف الذي أصابه ضرر ما لم يتضمن العقد تعويضا ً محددا مقابل إنهائه تعويضا على النحو الآتي:  
1-	أجر خمسة عشر يوما عن كل سنة من سنوات خدمة العامل إذا كان العقد غير محدد المدة.
2-	أجر المدة الباقية من العقد إذا كان العقد محدد المدة.
3-	يجب ألا يقل التعويض المشار إليه في الفقرتين (1,2) من هذه المادة عن أجر العامل لمدة شهرين وفقاً للمادة رقم (77) من نظام العمل. <br/>
4-	يستحق الطرف الثاني عند انتهاء خدمته مكافأة نهاية الخدمة وفقاً لما هو محدد في المواد أرقام (88،87،86،85،84) من نظام العمل. <br/>

البند العاشر: أحكام عامة:<br/><br/>
(‌أ)	يكون نظام العمل الصادر بالمرسوم الملكي رقم (م /51) وتاريخ 23/08/1426هـ والقرارات واللوائح التي يصدرها وزير العمل، وهو النظام الوحيد الذي يرجع إليه في كل ما يرد به نص في هذا العقد، وكل نزاع ينشأ بخصوص تفسير هذا العقد يكون الفصل فيه لهيئة تسوية الخلافات العمالية المختصة وفقاً للنظام. <br/>
(‌ب)	يعتبر التقويم المعمول به لدى الطرف الأول هو وحده الذي يتخذ أساساً لجميع التواريخ التي يتضمنها هذا العقد ما لم يتفقا على غير ذلك،  <br/> 
(‌ج)	يقر الموظف أن عنوانه الموضح بالعقد هو العنوان المختار، وكل إخطار/تبليغ أو خطاب يرسل إليه يكون نظامياً لآثاره ويجب على الموظف إخطار صاحب العمل بأي تغيير في هذا العنوان.<br/>

</p>

<p>(‌د)	يقر الطرف الثاني أنه على علم بأنه في حالة تركه العمل لدى الطرف الأول بعد مرور 12 شهراً من بدء دعم صندوق تنمية الموارد البشرية، فإنه لا يستحق دعم الصندوق مرة أخرى في أي منشأة خلال المدة التي يحددها الصندوق وفقاً لنظامه.<br/> 
(‌ه)	يحق لصندوق تنمية الموارد البشرية الاطلاع على حركات الحساب البنكي للطرف الثاني لغرض التأكد من إيداع الطرف الأول لرواتب وبدلات الطرف الثاني كاملة وذلك بالاتصال المباشر مع البنك دون الرجوع إلى الطرف الثاني، ويعتبر هذا إقرار موافقة منه. <br/>
¬
البند الحادي عشر: نسخ العقد:<br/><br/>
حرر هذا العقد من نسختين، ويسلم لكل طرف نسخة موقعة ومختومة. <br/>


الطرف الأول المدير التنفيذي				الطرف الثاني
الاســــم: 	نوال بنت محمد الشريف			الاســــم:	الاسم الثلاثي
التوقيع: 						التوقيع:
</p>';
$pdf->WriteHTML($htmlcontent1, true, 0, true, 0);

$pdf->Ln();

$htmlcontent2 = '';

$pdf->WriteHTML($htmlcontent2, true, 0, true, 0);

$pdf->Ln();

//Close and output PDF document
$pdf->Output('example_018.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+