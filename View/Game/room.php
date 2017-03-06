<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head lang="en">
    <meta charset="UTF-8">
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <title>Game</title>

</head>
<body>
<div align="center">
    <table border="1px">
        <tr>
            <td class="cell" data-cell="1" width="100px" height="100px">
            </td>
            <td class="cell" data-cell="2" width="100px" height="100px">
            </td>
            <td class="cell" data-cell="3" width="100px" height="100px">
            </td>
        </tr>
        <tr>
            <td class="cell" data-cell="4" width="100px" height="100px">
            </td>
            <td class="cell" data-cell="5" width="100px" height="100px">
            </td>
            <td class="cell" data-cell="6" width="100px" height="100px">
            </td>
        </tr>
        <tr>
            <td class="cell" data-cell="7" width="100px" height="100px">
            </td>
            <td class="cell" data-cell="8" width="100px" height="100px">
            </td>
            <td class="cell" data-cell="9" width="100px" height="100px">
            </td>
        </tr>
    </table>
</div>

<script>
    $('td.cell').on('click', function(){
        var clickedCell = $(this);
        if(clickedCell.hasClass('unclickable')){
            return;
        }
        clickedCell.addClass('unclickable');

        var cellName = $(this).data('cell');
        $.post('/game/onclick', {id:cellName}, function(answer){
            console.log(answer);
            var data = JSON.parse(answer)
            var imgHtml = '<img src="' + data.pic + '" alt="Zero/cross" width="100px">';
            clickedCell.html(imgHtml);
            if (data.success) {
                clickedCell.off('click');
            }
            if (data.win != '') {
                alert(data.win);
            }
        });
    });

    var intId = window.setInterval(function(){
        $.post('/game/game', {}, function(data){
            $('td.cell').each(function(){
                if (data.firstPlayerName == data.yourName) {
                }
            });
        });
    }, 1000);
</script>

</body>
</html>