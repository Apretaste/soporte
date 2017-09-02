{space10}

<center>
	{noimage width="100" height="100" text="@{$ticket->username}"}<br/>
	{$ticket->creation_date|date_format:"%d/%m/%Y %I:%M %p"}<br/>
	Estado: {tag caption="{$ticket->status}"}
</center>

{space10}

<p>{$ticket->body}</p>

{space10}

<center>
	{button href="SOPORTE" desc="Describa su problema, duda o sugerencia" caption="&#10010; Escribir" popup="true"}
	{button href="SOPORTE" caption="Atr&aacute;s" color="grey"}
</center>
