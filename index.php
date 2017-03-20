<?php
include_once "tools.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Page</title>
    <link href="http://cdn.bootcss.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="http://cdn.bootcss.com/jquery/3.2.0/jquery.min.js"></script>
</head>
<style type="text/css">
    .vertical-center{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 500;
    }
    body, .container {
        height: 100%;
    }
    .bg {
        position: absolute;
        width: 100%;
        height: 100%;
        left: 0;
        top: 0;
        background: url("background.jpg") no-repeat;
        background-size: 100% 100%;
        filter: blur(10px) brightness(0.75);
        z-index: -500;
    }
    h1 {
        color: white;
        text-align: center;
    }
    #display-container-parent{
        color: white;
        width: 100%;
        margin-top: 10px;
        text-align: center;
    }
    #relate-display-container-parent{
        color: white;
        width: 100%;
        margin-top: 10px;
        text-align: center;
    }
    .expert-label:hover {
        cursor: pointer;
        background: darkblue;
    }
    .page {
        opacity: 0.9;
        background: black;
        height: 100%;
        width: 100%;
        z-index: 800;
        position: absolute;
        left: 0;
        top: 0;
    }
    .close-btn:hover{
        cursor: pointer;
    }
    .btn:hover {
        cursor: pointer;
    }
    .user-container {
        top: 10px;
        right: 10px;
        position: absolute;
        color: white;
    }
</style>
<body>
<div class="bg"></div>
<?php if($user != null): ?>
<div class="user-container">
    Welcome, <?=$user["email"]?>
    <a class="btn btn-primary btn-sm" style="margin-left: 5px" href="path/auth.php?type=logout"><i class="fa fa-sign-out"></i>&nbsp;Log out</a>
</div>
<div class="container">
    <div class="row vertical-center">
        <div style="margin: 15px auto; text-align: center">
            <h1>AMiner Simple Search Engine</h1>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" id="search-field" placeholder="Type interest field to search...">
            <span class="input-group-btn">
                <button class="btn btn-primary mine-btn" type="button">Mine!</button>
            </span>
        </div>
        <div id="display-container-parent">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td>Name</td>
                        <td>H-index</td>
                        <td>Published Paper</td>
                        <td>Citations</td>
                    </tr>
                </thead>
                <tbody id="display-container">

                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="page" style="display: none">
    <div class="row vertical-center">
        <h1 class="waiting">Waiting...</h1>
        <h1 class="relate-error-msg" style="display: none;">Sorry. No related author found.</h1>
        <div class="related-authors" style="display: none">
            <h1>Related Authors</h1>
            <div id="relate-display-container-parent">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <td>Name</td>
                        <td>Cooperation</td>
                        <td>H-index</td>
                        <td>Published Paper</td>
                        <td>Citations</td>
                    </tr>
                    </thead>
                    <tbody id="relate-display-container">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="close-btn btn btn-primary" style="right: 20px; top: 20px; font-size: 20px; position: absolute; color: white"><i class="fa fa-remove"></i></div>
</div>
<script>
    function getAuthorTr(profile) {
        return "<tr class='expert-label' author-id='" + profile.index + "'><td>" + profile.n + "</td><td>" + profile.hi + "</td><td>" + profile.pc + "</td><td>" + profile.cn + "</td></tr>";
    }
    function getCoauthorTr(profile) {
        return "<tr class='expert-label' author-id='" + profile.index + "'><td>" + profile.n + "</td><td>" + profile.cnt + "</td><td>" + profile.hi + "</td><td>" + profile.pc + "</td><td>" + profile.cn + "</td></tr>";
    }

    function displayCoauthors(id) {
        $(".page").fadeIn(500);
        $(".relate-error-msg").hide();
        $(".related-authors").hide();
        $(".waiting").show();
        $("#relate-display-container").html("");
        $.get("getdata.php", {
            type: "coauthors",
            id: id
        }, function (data) {
            $(".waiting").hide();
            if (data.length == 0) {
                $(".relate-error-msg").fadeIn(500);
            } else {
                var html = "";
                for(var i=0;i<data.length;++i) {
                    html += getCoauthorTr(data[i]);
                }
                $("#relate-display-container").html(html);
                $(".related-authors").fadeIn(500);
                $(".expert-label").click(function () {
                    displayCoauthors($(this).attr('author-id'));
                });
            }
        },"json")
    }
    var temp;
    $(document).ready(function () {
        $("body").height($(window).height());
        $(".vertical-center").hide().fadeIn(1500);
        $(".mine-btn").click(function () {
            $(this).text("Searching...");
            $("#display-container").html("");
            $.get("getdata.php", {
                type: "interest",
                field: $("#search-field").val().replace(" ", "_")
            }, function (data) {
                $(".mine-btn").text("Mine!");
                if (data.length == 0) {
                    alert("NotFound")
                } else {
                    var html = "";
                    for(var i=0;i<data.length;++i) {
                        html += getAuthorTr(data[i]);
                    }
                    $("#display-container").html(html);
                    $(".expert-label").click(function () {
                        displayCoauthors($(this).attr('author-id'));
                    });
                }
            }, "json");
        });
        $(".close-btn").click(function () {
            $(this).parent().fadeOut(500)
        })
    })
</script>
<?php else: ?>
<div class="page" style="opacity: 0.75">
    <div class="vertical-center" style="text-align: center; align-content: center; display: none; color: white">
        <h1 style="margin-bottom: 20px">Please Sign In First</h1>
        <a href="path/auth.php" class="btn btn-primary"><i class="fa fa-github"></i>&nbsp;Sign In With Github</a>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(".vertical-center").fadeIn(2000);
    })
</script>
<?php endif ?>
</body>
</html>
