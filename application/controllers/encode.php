<? echo (”
<font face=\”Verdana\” size=\”2\”>
<FORM>
<b>Isi nilai string yang akan diproses</b><p>
<INPUT TYPE=TEXT NAME=code VALUE=\”$code\”>
<INPUT TYPE=SUBMIT NAME=action VALUE=\”Encode\”>
<INPUT TYPE=SUBMIT NAME=action VALUE=\”Decode\”>
</FORM><BR>
“);
if($action == “Encode”) {
echo “<B>Hasil:</FONT></B><BR>”;
echo base64_encode($code);
} elseif($action == “Decode”) {
echo “<B>Hasil:</FONT></B>
echo base64_decode($code);
} ?>