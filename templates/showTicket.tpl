<table width="100%">
	<tr>
		<td><h1>{$titulo}</h1></td>
		<td align="right" valign="top">
			{button href="SOPORTE reemplace este texto por una descripcion de su problema" size="small" body="Escriba en el asunto una descripcion detallada de su problema y envie este email" caption="&#10010; Agregar" popup="true"}
		</td>
	</tr>
</table>
<table width="100%" cellspacing="0" cellpadding="10" border="0" style="font-size:12px;">
		<tr style="background-color:#F2F2F2;">
			<!--<td>{$ticket->id}</td>-->
			
			<td colspan="5" style="font-weight: bold;" >
				{$ticket->subject}
			</td>
			<!--<td>{$ticket->creation_date|date_format:"%d/%m/%Y, %I:%M %p"}</td>-->
		</tr>
		<tr >
				<td colspan="2">{$userName|lower|capitalize} dijo:</td>
				<td colspan="3">{$ticket->body}</td>
		</tr>
		<tr style="background-color:#F2F2F2;font-size:9px;">
				<td colspan="1">Enviado:</td>
				<td colspan="2"><span style="">({$ticket->creation_date|date_format:"%d/%m/%Y %I:%M %p"})</span></td>
				<td colspan="2" align="right">Estatus: {$ticket->status}</td>
		</tr>
	{space10}
</table>