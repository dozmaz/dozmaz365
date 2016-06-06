
{{ content() }}

<form method="post">

<h2>Administrar permisos</h2>

<div class="well" align="center">

	<table class="perms">
		<tr>
			<td><label for="perfilesId">Perfil</label></td>
			<td>{{ select('perfilesId', perfiles, 'using': ['id', 'nombre'], 'useEmpty': true, 'emptyText': '...', 'emptyValue': '') }}</td>
			<td>{{ submit_button('Buscar', 'class': 'btn btn-primary') }}</td>
		</tr>
	</table>

</div>

{% if request.isPost() and perfil %}

{% for resource, actions in acl.getResources() %}

	<h3>{{ resource }}</h3>

	<table class="table table-bordered table-striped" align="center">
		<thead>
			<tr>
				<th width="5%"></th>
				<th>Descrici&oacute;n</th>
			</tr>
		</thead>
		<tbody>
			{% for action in actions %}
			<tr>
				<td align="center"><input type="checkbox" name="permisos[]" value="{{ resource ~ '.' ~ action }}"  {% if permissions[resource ~ '.' ~ action] is defined %} checked="checked" {% endif %}></td>
				<td>{{ acl.getActionDescription(action) ~ ' ' ~ resource }}</td>
			</tr>
			{% endfor %}
		</tbody>
	</table>

{% endfor %}

{% endif %}

</form>