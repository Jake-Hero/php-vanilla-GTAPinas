<?php 
    require __DIR__ . '/autoload.php';

    $dbClass = new DB(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
    $pdo = $dbClass->getConnection();
    $obj = new ucpProject($pdo);
    require_once __DIR__ . "/header.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

    <title><?php echo SITE_NAME; ?> - Skins</title>
</head>

<body>
    <main role="main" class="flex-grow-1 overflow-auto">
        <div class="container">
        <!-- Container -->
            <div class='alert alert-info'>
                <strong>Please <a href="https://www.open.mp/docs/scripting/resources/skins" target="_blank">click here</a> to check for original GTA-SA skin IDs.</strong> 
            </div>

            <div class="shadow-lg p-3 mb-5 bg-light rounded">
            <!-- Emulate Card -->
                <div class="table-responsive">
                    <table class="table table-hover text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>Skin ID</th>
                                <th>Model</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php for($i = 20123; $i <= 20136; $i++): ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><img src="<?php echo $obj->getSkinImage($i); ?>" alt="<?php echo $i; ?>" name="skin_pic" height="300" style="filter: drop-shadow(1px 1px 4px);" /></td>
                            </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>
            <!-- Emulate Card Ends here -->
            </div>
        <!-- Container Ends here -->
        </div>
    </main>

    <?php require DIR_INC . 'footer.php'; ?>

    <script type="text/javascript">
        $(document).ready(function() {
            var maleSkins = [1, 2, 3, 4, 5, 6, 7, 8, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 
            32, 33, 34, 35, 36, 37, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 57, 58, 59, 60, 61, 62, 
            66, 67, 68, 70, 71, 72, 73, 78, 79, 80, 81, 82, 83, 84, 86, 94, 95, 96, 97, 98, 99, 100, 101, 
            102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 
            121, 122, 123, 124, 125, 126, 127, 128, 129, 132, 133, 134, 135, 136, 137, 142, 143, 144, 146, 
            147, 149, 153, 154, 156, 158, 159, 160, 161, 162, 168, 170, 171, 173, 174, 175, 176, 177, 179, 
            180, 181, 182, 183, 184, 185, 186, 187, 188, 189, 200, 202, 206, 208, 209, 210, 212, 213, 220, 
            221, 222, 223, 227, 228, 229, 230, 234, 235, 236, 239, 240, 241, 242, 247, 248, 249, 250, 252, 
            253, 254, 255, 258, 259, 261, 262, 264, 269, 270, 271, 272, 273, 289, 299,
            20126, 20126, 20127, 20128, 20129, 20130, 20131, 20132, 20133, 20134, 20135, 20136];

            var femaleSkins = [9, 10, 11, 12, 13, 31, 38, 39, 40, 41, 53, 54, 55, 56, 63, 64, 65, 75, 76, 77, 85, 87, 88, 
            89, 90, 91, 92, 93, 129, 130, 131, 138, 139, 140, 141, 145, 148, 150, 151, 152, 157, 169, 
            172, 178, 190, 191, 192, 193, 194, 195, 196, 197, 198, 199, 201, 205, 207, 211, 214, 215, 
            216, 218, 219, 224, 225, 226, 231, 232, 233, 237, 238, 243, 244, 245, 246, 251, 256, 257, 
            263, 20123, 20124, 20125];

            var siteUrl = "<?php echo SITE_URL; ?>";

            $("#skinForm").prop('disabled', true);

            $("#genderForm").change(function(){
                var value = parseInt($(this).val());
                var picture = null;

                if(value == 1) {
                    picture = siteUrl + "/assets/pictures/skins/1.png";
                    $("#skinForm").prop('disabled', false);
                }
                else if(value == 2) {
                    picture = siteUrl + "/assets/pictures/skins/9.png";
                    $("#skinForm").prop('disabled', false);
                }
                else {
                    picture = siteUrl + "/assets/pictures/skins/0.png";
                    $("#skinForm").prop('disabled', true);
                }

                $("img[name=skin_pic]").attr("src", picture);
            });

            $("#skinForm").change(function(){
                var value = parseInt($(this).val());
                var gender = parseInt($("#genderForm").val());
                var isValid = -1;
                var picture = siteUrl + "/assets/pictures/skins/0.png";

                if(gender == 1) {
                    isValid = $.inArray(value, maleSkins);

                    if(isValid == -1) {
                        $("#skinForm").val(1);
                        value = $("#skinForm").val();
                    }

                    picture = siteUrl + "/assets/pictures/skins/" + value + ".png";
                    $("img[name=skin_pic]").attr("src", picture);
                }

                if(gender == 2) {
                    isValid = $.inArray(value, femaleSkins);

                    if(isValid == -1) {
                        $("#skinForm").val(9);
                        value = $("#skinForm").val();
                    }

                    picture = siteUrl + "/assets/pictures/skins/" + value + ".png";
                    $("img[name=skin_pic]").attr("src", picture);
                }
            });

            $('#createBtn').on('click', function(e) {
                e.preventDefault();

                var name = document.getElementById('charnameForm').value;
                var skin = document.getElementById('skinForm').value;
                var gender = document.getElementById('genderForm').value;
                var bday = document.getElementById('bdayForm').value;
                var slot = document.getElementById('slotForm').value;

                var dateParts = bday.split('/');
                var formattedDate = '';
                if(dateParts.length === 3) {
                    var day = dateParts[1];
                    var month = dateParts[0];
                    var year = dateParts[2];
                    formattedDate = day + '-' + month + '-' + year;
                }

                var dataString='slot='+ slot + '&name=' + name + '&gender=' + gender + '&birthday=' + formattedDate + '&skin=' + skin;

                $.ajax(
                {
                    type:"post",
                    url: "ajax/ajax_create_character.php",
                    data: dataString,
                    success: function(data)
                    {
                        if(data !== 'Success') {
                            $("#ajax").html(data);
                        }
                        else {
                            Swal.fire({
                                title: "Created",
                                text: "Successfully created a new character.",
                                icon: "success"
                            }).then((result) => {
                                window.location.href = "user/dashboard.php";
                            });
                        }
                        //window.location.href = "user/dashboard.php";
                        //console.log(data);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });
            });

            $('[data-toggle="datepicker"]').datepicker();
        });
    </script>
</body>
</html>