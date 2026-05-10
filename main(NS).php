<?php
require_once __DIR__ . '/security/auth.php';
require_login();

include("db.php");

$userID = (int)$_SESSION["UserID"];

/* -------------------------
   GET USER INFORMATION
--------------------------*/
$stmt = $mysqli->prepare("SELECT email, FirstName FROM Persons WHERE UserID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

/* -------------------------
   DAILY CHECK-IN SYSTEM
--------------------------*/
$checkinMessage = "";
$points = 0;
$lastCheck = null;

$stmt = $mysqli->prepare("SELECT points, last_checkin FROM rewards WHERE UserID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $stmt->close();
    $stmt = $mysqli->prepare("INSERT INTO rewards(UserID, points, last_checkin) VALUES (?, 0, NULL)");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
} else {
    $data = $result->fetch_assoc();
    $points = (int)$data["points"];
    $lastCheck = $data["last_checkin"];
}
$stmt->close();

if (isset($_POST["checkin"])) {

    // CSRF check
    $token = $_POST["csrf_token"] ?? "";
    if (!csrf_verify($token)) {
        $checkinMessage = "Invalid request.";
    } else {
        $today = date("Y-m-d");

        if ($lastCheck !== $today) {
            $points += 10;

            $stmt = $mysqli->prepare("UPDATE rewards SET points = ?, last_checkin = ? WHERE UserID = ?");
            $stmt->bind_param("isi", $points, $today, $userID);
            $stmt->execute();
            $stmt->close();

            $lastCheck = $today;
            $checkinMessage = "Check-in successful! +10 points";
        } else {
            $checkinMessage = "You already checked in today";
        }
    }
}

/* -------------------------
   SEARCH SYSTEM
--------------------------*/
$searchResults = null;
$searchQuery = "";

if (isset($_GET["search"])) {
    $searchQuery = trim($_GET["search"]);
    if ($searchQuery !== "") {
        $keyword = "%" . $searchQuery . "%";
        $stmt = $mysqli->prepare("SELECT CourseName, Description FROM Courses WHERE CourseName LIKE ?");
        $stmt->bind_param("s", $keyword);
        $stmt->execute();
        $searchResults = $stmt->get_result();
        $stmt->close();
    }
}

/* -------------------------
   TRANSLATION SYSTEM
--------------------------*/
$translations = [
    "en" => [
        "selectLang" => "Select Language",
        "navHome" => "Home",
        "navCourses" => "Courses",
        "navInbox" => "Inbox",
        "navStudents" => "Students",
        "navClubs" => "Clubs",
        "navSupport" => "Support",
        "navLogout" => "Logout",
        "search_placeholder" => "Search...",
        "search_btn" => "Search",
        "welcome" => "Welcome to WLV Web Page",
        "subtitle" => "Explore courses, campus clubs, and student services.",
        "servicesTitle" => "Our Services",
        "card_courses" => "Courses",
        "card_courses_desc" => "High quality academic courses.",
        "card_library" => "Library",
        "card_library_desc" => "Digital resources & materials.",
        "card_support" => "Support",
        "card_support_desc" => "24/7 help & assistance",
        "search_results" => "Search Results"
    ],
    "es" => [
        "selectLang" => "Seleccionar idioma",
        "navHome" => "Inicio",
        "navCourses" => "Cursos",
        "navInbox" => "Bandeja de entrada",
        "navStudents" => "Estudiantes",
        "navClubs" => "Clubes",
        "navSupport" => "Soporte",
        "navLogout" => "Cerrar sesión",
        "search_placeholder" => "Buscar...",
        "search_btn" => "Buscar",
        "welcome" => "Bienvenido a la página WLV",
        "subtitle" => "Explora cursos, clubes del campus y servicios estudiantiles.",
        "servicesTitle" => "Nuestros Servicios",
        "card_courses" => "Cursos",
        "card_courses_desc" => "Cursos académicos de alta calidad.",
        "card_library" => "Biblioteca",
        "card_library_desc" => "Recursos y materiales digitales.",
        "card_support" => "Soporte",
        "card_support_desc" => "Ayuda y asistencia 24/7",
        "search_results" => "Resultados de búsqueda"
    ],
    "fr" => [
        "selectLang" => "Choisir la langue",
        "navHome" => "Accueil",
        "navCourses" => "Cours",
        "navInbox" => "Boîte de réception",
        "navStudents" => "Étudiants",
        "navClubs" => "Clubs",
        "navSupport" => "Support",
        "navLogout" => "Déconnexion",
        "search_placeholder" => "Rechercher...",
        "search_btn" => "Rechercher",
        "welcome" => "Bienvenue sur la page WLV",
        "subtitle" => "Explorez les cours, clubs et services étudiants.",
        "servicesTitle" => "Nos Services",
        "card_courses" => "Cours",
        "card_courses_desc" => "Cours académiques de haute qualité.",
        "card_library" => "Bibliothèque",
        "card_library_desc" => "Ressources numériques.",
        "card_support" => "Support",
        "card_support_desc" => "Aide et assistance 24h/24",
        "search_results" => "Résultats de recherche"
    ],
    "de" => [
        "selectLang" => "Sprache auswählen",
        "navHome" => "Startseite",
        "navCourses" => "Kurse",
        "navInbox" => "Posteingang",
        "navStudents" => "Studenten",
        "navClubs" => "Clubs",
        "navSupport" => "Support",
        "navLogout" => "Abmelden",
        "search_placeholder" => "Suchen...",
        "search_btn" => "Suchen",
        "welcome" => "Willkommen auf der WLV Webseite",
        "subtitle" => "Entdecken Sie Kurse, Campus-Clubs und Studentendienste.",
        "servicesTitle" => "Unsere Dienstleistungen",
        "card_courses" => "Kurse",
        "card_courses_desc" => "Hochwertige akademische Kurse.",
        "card_library" => "Bibliothek",
        "card_library_desc" => "Digitale Ressourcen & Materialien.",
        "card_support" => "Support",
        "card_support_desc" => "24/7 Hilfe & Unterstützung",
        "search_results" => "Suchergebnisse"
    ],
    "zh" => [
        "selectLang" => "选择语言",
        "navHome" => "主页",
        "navCourses" => "课程",
        "navInbox" => "收件箱",
        "navStudents" => "学生",
        "navClubs" => "社团",
        "navSupport" => "支持",
        "navLogout" => "退出登录",
        "search_placeholder" => "搜索...",
        "search_btn" => "搜索",
        "welcome" => "欢迎来到 WLV 网站",
        "subtitle" => "探索课程、校园社团和学生服务。",
        "servicesTitle" => "我们的服务",
        "card_courses" => "课程",
        "card_courses_desc" => "高质量学术课程。",
        "card_library" => "图书馆",
        "card_library_desc" => "数字资源 & 材料。",
        "card_support" => "支持",
        "card_support_desc" => "全天候帮助 & 支持",
        "search_results" => "搜索结果"
    ],
    "ar" => [
        "selectLang" => "اختر اللغة",
        "navHome" => "الرئيسية",
        "navCourses" => "الدورات",
        "navInbox" => "صندوق الوارد",
        "navStudents" => "الطلاب",
        "navClubs" => "الأندية",
        "navSupport" => "الدعم",
        "navLogout" => "تسجيل الخروج",
        "search_placeholder" => "بحث...",
        "search_btn" => "بحث",
        "welcome" => "مرحبًا بكم في صفحة WLV",
        "subtitle" => "استكشف الدورات والأندية وخدمات الطلاب.",
        "servicesTitle" => "خدماتنا",
        "card_courses" => "الدورات",
        "card_courses_desc" => "دورات أكاديمية عالية الجودة.",
        "card_library" => "المكتبة",
        "card_library_desc" => "الموارد الرقمية & المواد.",
        "card_support" => "الدعم",
        "card_support_desc" => "مساعدة & دعم 24/7",
        "search_results" => "نتائج البحث"
    ],
    "ne" => [
        "selectLang" => "भाषा चयन गर्नुहोस्",
        "navHome" => "घर",
        "navCourses" => "पाठ्यक्रम",
        "navInbox" => "इनबक्स",
        "navStudents" => "विद्यार्थी",
        "navClubs" => "क्लबहरू",
        "navSupport" => "समर्थन",
        "navLogout" => "लगआउट",
        "search_placeholder" => "खोज्नुहोस्...",
        "search_btn" => "खोज्नुहोस्",
        "welcome" => "WLV वेब पृष्ठमा स्वागत छ",
        "subtitle" => "पाठ्यक्रम, क्याम्पस क्लब र सेवाहरू अन्वेषण गर्नुहोस्।",
        "servicesTitle" => "हाम्रा सेवाहरू",
        "card_courses" => "पाठ्यक्रम",
        "card_courses_desc" => "उच्च गुणस्तरको शैक्षिक पाठ्यक्रम।",
        "card_library" => "पुस्तकालय",
        "card_library_desc" => "डिजिटल स्रोतहरू & सामग्रीहरू।",
        "card_support" => "समर्थन",
        "card_support_desc" => "२४/७ सहायता & समर्थन",
        "search_results" => "खोज परिणामहरू"
    ]
];

// Default language
$lang = "en";
if (isset($_GET["lang"]) && isset($translations[$_GET["lang"]])) {
    $lang = $_GET["lang"];
}

$t = $translations[$lang];
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>WLV Home</title>
<link rel="stylesheet" href="main_style.css">
<style>
.content-wrapper {display:flex; flex-wrap:wrap; gap:20px; padding:20px; align-items:flex-start;}
.main-content {flex:1; min-width:300px;}
.header-section {display:flex; flex-wrap:wrap; gap:20px; align-items:center; margin:20px 0;}
.header-text {flex:1;}
.header-img {flex:0 0 300px;}
.header-img img {max-width:100%; height:auto; border-radius:8px;}
.services-grid {display:flex; flex-wrap:wrap; gap:20px;}
.service-card {flex:1 1 200px; padding:20px; border-radius:8px; background:#f0f0f0; text-decoration:none; color:#000;}
.checkin-box {margin-top:10px; padding:10px; background:#d4edda; color:#155724; border-radius:5px;}
.result-card {border:1px solid #ddd; padding:10px; margin:10px 0; border-radius:5px;}
</style>
</head>
<body>

<!-- LANGUAGE SELECT -->
<div style="text-align:right; padding:10px;">
<select id="languageSelect" onchange="setLanguage(this.value)">
<?php foreach($translations as $code=>$val){ ?>
<option value="<?php echo htmlspecialchars($code); ?>" <?php if($code==$lang) echo "selected"; ?>>
    <?php echo htmlspecialchars(strtoupper($code)); ?>
</option>
<?php } ?>
</select>
</div>

<!-- NAVIGATION -->
<nav class="navbar">
<div class="logo">WLV | <?php echo htmlspecialchars($user["email"] ?? ""); ?></div>
<div class="nav-links">
    <a href="main(NS).php?lang=<?php echo htmlspecialchars($lang); ?>"><?php echo htmlspecialchars($t["navHome"]); ?></a>
    <a href="courses(TD).php"><?php echo htmlspecialchars($t["navCourses"]); ?></a>
    <a href="inbox.php">Inbox</a>
    <a href="clubs(MR).html"><?php echo htmlspecialchars($t["navClubs"]); ?></a>
    <a href="contact(NS).php"><?php echo htmlspecialchars($t["navSupport"]); ?></a>
    <a href="logout(NS).php">Logout</a>
</div>

<form class="search-box" method="GET" action="main(NS).php">
    <input type="hidden" name="lang" value="<?php echo htmlspecialchars($lang); ?>">
    <input type="text" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="<?php echo htmlspecialchars($t["search_placeholder"]); ?>">
    <button type="submit"><?php echo htmlspecialchars($t["search_btn"]); ?></button>
</form>
</nav>

<!-- PAGE CONTENT -->
<div class="content-wrapper">
<div class="main-content">

<section class="header-section">
<div class="header-text">
    <h1><?php echo htmlspecialchars($t["welcome"]); ?>, <?php echo htmlspecialchars($user["FirstName"] ?? ""); ?></h1>
    <p><?php echo htmlspecialchars($t["subtitle"]); ?></p>

    <section class="checkin-section">
        <h2>Reward Points</h2>
        <p><strong><?php echo (int)$points; ?> Points</strong></p>

        <form method="POST" action="main(NS).php?lang=<?php echo htmlspecialchars($lang); ?>">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
            <button type="submit" name="checkin">Daily Check-In</button>
        </form>

        <?php if ($checkinMessage): ?>
            <div class="checkin-box"><?php echo htmlspecialchars($checkinMessage); ?></div>
        <?php endif; ?>
    </section>
</div>

<div class="header-img">
    <img src="https://pxl-wlvacuk.terminalfour.net/fit-in/1500x10000/prod01/wlvacuk/media/departments/digital-content-and-communications/images-2024/City-courtyard.jpg"
         alt="University of Wolverhampton">
</div>
</section>

<!-- SERVICES GRID -->
<section class="services">
<h2><?php echo htmlspecialchars($t["servicesTitle"]); ?></h2>

<div class="services-grid">
    <a href="courses(TD).php" class="service-card">
        <h3><?php echo htmlspecialchars($t["card_courses"]); ?></h3>
        <p><?php echo htmlspecialchars($t["card_courses_desc"]); ?></p>
    </a>

    <a href="library(NS).php" class="service-card">
        <h3><?php echo htmlspecialchars($t["card_library"]); ?></h3>
        <p><?php echo htmlspecialchars($t["card_library_desc"]); ?></p>
    </a>

    <a href="contact(NS).php" class="service-card">
        <h3><?php echo htmlspecialchars($t["card_support"]); ?></h3>
        <p><?php echo htmlspecialchars($t["card_support_desc"]); ?></p>
    </a>

    <a href="events(TD).html" class="service-card">
        <h3>Events</h3>
        <p>Things going on in the future</p>
    </a>
</div>
</section>

<!-- SEARCH RESULTS -->
<?php if ($searchResults && $searchResults->num_rows > 0): ?>
<section class="search-results">
<h2><?php echo htmlspecialchars($t["search_results"]); ?></h2>
<?php while ($row = $searchResults->fetch_assoc()): ?>
    <div class="result-card">
        <h3><?php echo htmlspecialchars($row["CourseName"]); ?></h3>
        <p><?php echo htmlspecialchars($row["Description"]); ?></p>
    </div>
<?php endwhile; ?>
</section>
<?php endif; ?>

</div>
</div>

<footer>
<p>© 2026 WLV Web Page | All Rights Reserved</p>
</footer>

<script>
function setLanguage(lang){
    const params = new URLSearchParams(window.location.search);
    params.set('lang', lang);
    // keep search param if present
    window.location.search = params.toString();
}
</script>

</body>
</html>
