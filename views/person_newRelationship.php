<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Naslov</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.js"></script>

    <!--<link rel="stylesheet" type="text/css" href="./hello.css">-->
</head>
<body>
<!--pretpostavljamo: "person":personInfo, "parents":conn1, "partners":partnerConn, "children":conn2
također pretpostavljamo da je atribut veze pod 'atribut', a osoba pod 'person'
traži se i id osobe s kojom imamo vezu (za modify)-->

<form>
    <table>
        <tr>
            <td>Type of relationship: </td>
            <td>
                <select name="relationshipType">
                    <option value="offspring" selected>Child</option>
                    <option value="partner">Partner</option>
                </select>
            </td>
        </tr>
        <tr>
            <td id="set"></td>
            <td><label><input type="checkbox" name="setValue" value="1" />Set</label></td>
        </tr>
        <tr>
            <td>Find person: </td>
            <td>
                First name: <input type="text" name="inputFirst" value="" /><br>
                Last name: <input type="text" name="inputLast" value="" /><br>
            </td>
        </tr>
    </table><br>

    <div id="searchPerson"></div><br>
    
    <button type="submit" name="add" id="add" disabled>Add</button>
</form>

<script>
$(document).ready(function() {
    $( "select" ).change(function () {
        var str = "";
        $( "select option:selected" ).each(function() {
            if( $( this ).val() === "offspring" )
                str = "Adopted";
            else str = "Married";
        });
        $( "#set" ).text( str );
    }).change();

    $()
});
</script>

</body>
</html>