<!DOCTYPE html>
<html lang="en">

<head>

<meta name="google-site-verification" content="pHOKbCxEdnzOgMk_lv83CZXGUhz0F_YfL-BQVQYndhc" />
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Sports Meet Performance Tracker</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>

body{
margin:0;
font-family:'Segoe UI',sans-serif;
height:100vh;
overflow:hidden;
background:linear-gradient(-45deg,#1e3c72,#2a5298,#0f2027,#203a43);
background-size:400% 400%;
animation:gradientMove 10s ease infinite;
color:white;
}

@keyframes gradientMove{

0%{background-position:0% 50%;}
50%{background-position:100% 50%;}
100%{background-position:0% 50%;}

}

.circle{
position:absolute;
border-radius:50%;
background:rgba(255,255,255,0.1);
animation:float 8s infinite ease-in-out;
}

.circle1{
width:200px;
height:200px;
top:10%;
left:5%;
}

.circle2{
width:150px;
height:150px;
bottom:10%;
right:10%;
}

@keyframes float{

0%{transform:translateY(0);}
50%{transform:translateY(-30px);}
100%{transform:translateY(0);}

}

.main-container{

display:flex;
justify-content:center;
align-items:center;
height:100vh;

}

.glass-box{

background:rgba(255,255,255,0.1);
backdrop-filter:blur(10px);
padding:50px;
border-radius:20px;
width:420px;
text-align:center;
box-shadow:0 10px 40px rgba(0,0,0,0.3);

}

.btn-custom{

width:100%;
margin-top:15px;
padding:12px;
font-size:18px;
border:none;
border-radius:8px;
transition:0.3s;

}

.admin-btn{
background:#ff4b2b;
color:white;
}

.user-btn{
background:#007bff;
color:white;
}

.register-btn{
background:#28a745;
color:white;
}

.btn-custom:hover{
transform:scale(1.05);
}

</style>

</head>

<body>

<div class="circle circle1"></div>
<div class="circle circle2"></div>

<div class="main-container">

<div class="glass-box animate__animated animate__fadeInDown">

<h1>🏆 Sports Meet</h1>

<p>Performance Tracking System</p>

<a href="sports.admin.portal.login.php">
<button class="btn-custom admin-btn">
<i class="fa fa-user-shield"></i> Admin Login
</button>
</a>

<a href="member.login.php">
<button class="btn-custom user-btn">
<i class="fa fa-user"></i> User Login
</button>
</a>

<a href="member.register.php">
<button class="btn-custom register-btn">
<i class="fa fa-user-plus"></i> Register
</button>
</a>

</div>

</div>

</body>
</html>