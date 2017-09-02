<h1>Sus peticiones</h1>

{space5}

<table width="100%" cellspacing="0" cellpadding="10" border="0">
	{foreach from=$tickets key=key item=ticket}

	{assign var="bgcolor" value="#C1C1C1"}
	{assign var="person" value="AP"}
	{if $ticket->from == $userEmail}
		{assign var="bgcolor" value="white"}
		{assign var="person" value="YO"}
	{/if}

	<tr bgcolor="{$bgcolor}">
		<!--image-->
		<td align="left">{noimage width="50" height="50" text="{$person}"}</td>
		<!--ticket-->
		<td width="100%">
			<small><font color="gray">{$ticket->creation_date|date_format:"%d/%m/%Y %I:%M %p"}</font></small><br/>
			{$ticket->body|truncate:100:"... "}
		</td>
		<!--button-->
		<td align="right">{button href="SOPORTE {$ticket->id}" caption="Leer" size="small"}</td>
	</tr>
	{/foreach}
</table>

{space10}

<center>
	{button href="SOPORTE" desc="Describa su problema, duda o sugerencia" caption="&#10010; Escribir" popup="true"}
</center>
