<?php  
include '../includes/header.php'; 
?>

<link rel="stylesheet" href="../includes/headerstyle.css">
<style>
/* Reset container */
.reset-container {
  width: 350px;
  margin: 80px auto;
  padding: 30px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  text-align: center;
}

/* Heading */
.reset-container h2 {
  margin-bottom: 20px;
  color: #0f9691;   /* teal hospital theme */
  font-size: 24px;
  font-weight: bold;
}

/* Input field */
.reset-container input[type="email"] {
  width: 90%;
  padding: 12px;
  margin: 12px 0;
  border: 1px solid #ccc;
  border-radius: 6px;
  outline: none;
  transition: border 0.3s;
}

.reset-container input[type="email"]:focus {
  border: 1px solid #0f9691;
}

/* Reset button */
.reset-container button {
  width: 95%;
  padding: 14px;
  background: linear-gradient(135deg, #0f9691, #0c7a76); /* teal gradient */
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 16px;
  font-weight: bold;
  transition: all 0.3s ease;
  box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.reset-container button:hover {
  background: linear-gradient(135deg, #0c7a76, #095f5b);
  transform: translateY(-2px) scale(1.05);
  box-shadow: 0 6px 12px rgba(0,0,0,0.25);
}

/* Footer fix */
html, body {
  height: 100%;
  margin: 0;
  display: flex;
  flex-direction: column;
}

footer {
  background: #0f9691;
  color: #fff;
  text-align: center;
  padding: 12px;
  margin-top: auto;   /* keeps footer at bottom */
  width: 100%;
}
</style>

<div class="reset-container">
  <h2>Forgot Password</h2>
  <form method="POST" action="send_reset.php">
    <input type="email" name="email" placeholder="Enter your email" required>
    <button type="submit">Reset Password</button>
  </form>
</div>

<?php include '../includes/footer.php'; ?>