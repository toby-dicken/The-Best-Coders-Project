<?php
session_start();
include("db.php");

if(!isset($_SESSION["UserID"])){
    header("Location: login(NS).php");
    exit();
}

$userID = $_SESSION["UserID"];

/* USER */
$stmt = $mysqli->prepare("SELECT FirstName, role, course FROM Persons WHERE UserID=?");
$stmt->bind_param("i",$userID);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$isAdmin = ($user["role"] == "admin");

/* FIX: fallback if course is NULL */
$userCourse = $user["course"];
if($userCourse == NULL && !$isAdmin){
    $userCourse = "Computer Science";
}

/* VIEW MODE */
if(!isset($_SESSION["view_mode"])){
    $_SESSION["view_mode"] = $user["role"];
}
$viewMode = $_SESSION["view_mode"];

/* MODULE SELECT */
$module_id = isset($_GET["module_id"]) ? intval($_GET["module_id"]) : null;

/* ================= UPLOAD FILE ================= */
if($isAdmin && $viewMode=="admin" && isset($_POST["upload"])){

    $title = $_POST["title"];
    $module_id_post = intval($_POST["module_id"]);

    if($module_id_post <= 0){
        die("❌ Select a module first");
    }

    if(!is_dir("uploads")){
        mkdir("uploads", 0777, true);
    }

    $fileName = basename($_FILES["file"]["name"]);
    $tmp = $_FILES["file"]["tmp_name"];

    $newName = time() . "_" . $fileName;
    $path = "uploads/" . $newName;

    move_uploaded_file($tmp, $path);

    /* FIXED INSERT (ONLY file_path is needed for access) */
    $stmt = $mysqli->prepare("
        INSERT INTO course_files 
        (file_path, title, module_id, uploaded_by, upload_date)
        VALUES (?, ?, ?, ?, NOW())
    ");

    $stmt->bind_param(
        "ssis",
        $path,
        $title,
        $module_id_post,
        $user["FirstName"]
    );

    $stmt->execute();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Courses</title>

<style>
body{
margin:0;
font-family:Segoe UI;
background: linear-gradient(135deg,#4facfe,#00f2fe);
color:white;
}

.navbar{
display:flex;
justify-content:space-between;
padding:15px;
background:rgba(0,0,0,0.6);
}

.container{padding:20px;}

.card{
background:rgba(255,255,255,0.15);
padding:15px;
margin:10px;
border-radius:12px;
cursor:pointer;
}

.file{
background:rgba(0,0,0,0.3);
padding:10px;
margin:8px 0;
border-radius:8px;
}

a{color:white;text-decoration:none;}

button{
padding:10px;
background:#00f2fe;
border:none;
border-radius:6px;
cursor:pointer;
width:100%;
}

input,select{
width:100%;
padding:8px;
margin:5px 0;
}
</style>
</head>

<body>

<div class="navbar">
<div><a href="main(NS).php">Home</a></div>
<div>WLV Courses - <?php echo $userCourse; ?></div>
<div><?php echo $user["FirstName"]; ?></div>
</div>

<div class="container">

<!-- ================= MODULE LIST ================= -->
<?php if(!$module_id){ ?>

<h1>Modules</h1>

<?php
if($isAdmin){
    $modules = $mysqli->query("SELECT * FROM modules");
} else {
    $stmt = $mysqli->prepare("SELECT * FROM modules WHERE course_name=?");
    $stmt->bind_param("s",$userCourse);
    $stmt->execute();
    $modules = $stmt->get_result();
}

while($m = $modules->fetch_assoc()){
?>

<a href="courses(NS).php?module_id=<?php echo $m['id']; ?>">

<div class="card">
<h3><?php echo $m["module_name"]; ?></h3>
<p>Click to open module</p>
</div>

</a>

<?php } ?>

<!-- ================= UPLOAD ================= -->
<?php if($isAdmin && $viewMode=="admin"){ ?>

<div class="card">

<h3>Upload File</h3>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="title" placeholder="File Title" required>

<select name="module_id" required>
<option value="">-- Select Module --</option>

<?php
$mods = $mysqli->query("SELECT id, module_name FROM modules");
while($m = $mods->fetch_assoc()){
?>
<option value="<?php echo $m['id']; ?>">
    <?php echo $m['module_name']; ?>
</option>
<?php } ?>

</select>

<input type="file" name="file" required>

<button name="upload">Upload</button>

</form>

</div>

<?php } ?>

<?php } ?>

<!-- ================= MODULE FILE VIEW ================= -->
<?php if($module_id){ ?>

<a href="courses(NS).php">⬅ Back</a>

<h1>Module Files</h1>

<?php
$stmt = $mysqli->prepare("
SELECT * FROM course_files 
WHERE module_id=?
ORDER BY upload_date DESC
");
$stmt->bind_param("i",$module_id);
$stmt->execute();
$files = $stmt->get_result();
?>

<?php if($files->num_rows > 0){ ?>

<?php while($f = $files->fetch_assoc()){ ?>

<div class="file">

📄 <b><?php echo $f["title"]; ?></b><br>
<small>Uploaded by: <?php echo $f["uploaded_by"]; ?></small><br>

<!-- FIXED ACCESS -->
<a href="<?php echo $f["file_path"]; ?>" target="_blank">
👁 View File
</a>

&nbsp; | &nbsp;

<a href="<?php echo $f["file_path"]; ?>" download>
⬇ Download
</a>

</div>

<?php } ?>

<?php } else { ?>
<p>No files in this module.</p>
<?php } ?>

<?php } ?>

</div>

</body>
</html>