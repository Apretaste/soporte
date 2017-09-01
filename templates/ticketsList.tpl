<table width="100%">
	<tr>
		<td><h1>Lista de tickets de soporte:</h1></td>
		<td align="right" valign="top">
			{button href="SOPORTE reemplace este texto por una descripcion de su problema" size="small" body="Escriba en el asunto una descripcion detallada de su problema y envie este email" caption="&#10010; Agregar" popup="true" noresponse="true"}
		</td>
	</tr>
</table>
<table width="100%" cellspacing="0" cellpadding="10" border="0" style="font-size:12px;">
{foreach from=$tickets key=key item=ticket}
		<tr style="background-color:#F2F2F2;">
			<!--<td>{$ticket->id}</td>-->
			
			<td colspan="6" style="font-weight: bold;" {if $ticket->from != $userEmail}align="right"{/if}>
				{$ticket->subject}<br>
				<span style="font-size:9px;">({$ticket->creation_date|date_format:"%d/%m/%Y %I:%M %p"})</span>
			</td>
			<!--<td>{$ticket->status}</td>-->
			<!--<td>{$ticket->creation_date|date_format:"%d/%m/%Y, %I:%M %p"}</td>-->
		</tr>
		<tr >
			{if $ticket->from == $userEmail}
				{if $ticket->body|strlen > 300}
				{else}
				{/if}
				<td colspan="2">{$ticket->body|truncate:300:"... "} <span style="font-size:9px;">{link href="SOPORTE {$ticket->id}" caption="Ver m&aacute;s"}</span></td>
				<td width="30%"></td>
			{else}
				<td width="30%" align="right"></td>
				<td align="right" colspan="2">{$ticket->body|truncate:300:"... "} <span style="font-size:9px;">{link href="SOPORTE {$ticket->id}" caption="Ver m&aacute;s"}</span></td>
				<!--<td >{$userName|lower|capitalize}:</td>-->
			{/if}
			<!--<td></td>
			<td></td>
			<td></td>
			{if $ticket@iteration is odd}style="background-color:#F2F2F2;"{/if}
			-->
		</tr>
	
	{space10}
	{space10}
{/foreach}
</table>