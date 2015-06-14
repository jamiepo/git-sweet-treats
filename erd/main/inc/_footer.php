<?
if (isset($conn) && $conn->IsConnected())  {
	$conn->Close(); # optional
}
?>
</div>
<div id='foot'>
	<a class='subtle' href='http://<?=MY_WEBSITE?>'><?=MY_PROJECT?>: <?=MY_VERSION?></a>
	<a href='http://sourceforge.net/projects/adodb/' target='_blank'><img
		src='<?=ADODB?>/cute_icons_for_site/adodb.gif'
		alt='ADODB' /></a>
	<a class='subtle' href='http://<?=MY_WEBSITE?>'><?=MY_WEBSITE?></a>
</div>
</body></html>