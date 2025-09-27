
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="doctorPanel.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="main">
        <div class="title">Application Form </div>
        <form action="connection.php" method="post">
        <div class="form">
            <div class="inputf">
                <label>Name:</label>
                <input type="text" class="input" name="name">
            </div>
            <div class="inputf">
                <label>Age:</label>
                <input type="text" class="input" name="age">
            </div>
             <div class="inputf">
                <label>Gender:</label>
                <select name="" id="" class="box" name="gender">
                    <option value="not selected">Select:</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Others</option>

                </select>
            </div>
            <div class="inputf">
                <label>Address:</label>
                <textarea name="" id="" class="textarea" name="address"></textarea>
            </div>
            <div class="inputf">
                <label>Number:</label>
                <input type="text" class="input" name="nmbr">
            </div>
            <div class="inputf">
                <label>Qualification:</label>
                <input type="text" class="input" name="qualification">
            </div>
            <div class="inputf">
                <label>Specialization:</label>
                <input type="text" class="input" name="specialization">
            </div>
            <div class="inputf">
                <label>Experience:</label>
                <input type="text" class="input" name="experience">
            </div>
            <div class="inputf">
                <label>CV:</label>
                <input type="text" class="input" name="cv">
            </div>
            <div class="inputf">
                <input type="submit" class="btn" placeholder="Submit" value="Register" name="register">
            </div>
        </div>
    </form>
    </div>
</body>
</html>
