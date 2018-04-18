{{ content() }}
{% for fecha in fechas %}
    {% if pronosticos.permitidoModificar(fecha.FECHA_PARTIDO ~ " 23:59:59") %}
        {% if fechaSeleccionada == fecha.FECHA_PARTIDO %}
            {{ link_to("pronosticos/index/" ~ fecha.FECHA_PARTIDO, fecha.FECHA_PARTIDO, 'class':'btn btn-primary') }}
        {% else %}
            {{ link_to("pronosticos/index/" ~ fecha.FECHA_PARTIDO, fecha.FECHA_PARTIDO, 'class':'btn btn-default') }}
        {% endif %}
    {% endif %}
{% else %}
    No hay partidos pendientes
{% endfor %}


{{ form("pronosticos/save", "id":"formBoleta", "class":"form-horizontal form-label-left") }}
<input type="hidden" name="fechaSeleccionada" value="{{ fechaSeleccionada }}">

<div class="row">
    {% for partido in partidosDelDia %}

        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">{{ partido.FECHA }}
                    <small>{{ partido.FASES.NOMBRE }}</small>
                </h3>

                <div class="container">
                    <div class="row">
                        <div class="col-5">
                            <div class="row">
                                <div class="col-12 col-sm-8">
                                    <div class="row">
                                        <div class="btn-group" role="group" aria-label="First group">
                                            <button type="button"
                                                    class="btn btn-light">{{ partido.getLocal().NOMBRE }}</button>
                                            <button type="button" class="btn btn-light">
                                                <img src="data:image/jpeg;base64,{{ base64_encode(partido.getLocal().LOGO) }}"
                                                     class="img-responsive" width="25"/>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="row">
                                        <div class="form-group">
                                            {% if pronosticos.permitidoModificar(partido.FECHA) %}
                                                {{ numeric_field("LOCAL_" ~ partido.PARTIDOS_ID ~"_" ~ partido.LOCAL,  'min':"0", 'max':"100", 'class':"form-control text-center", "required":"required") }}
                                            {% else %}
                                                {{ text_field("LOCAL_" ~ partido.PARTIDOS_ID ~"_" ~ partido.LOCAL, 'readonly':"readonly", 'class':"form-control text-center", 'id':"", 'name':"") }}
                                            {% endif %}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-2 text-center">
                            <b>VS</b>
                        </div>
                        <div class="col-5">
                            <div class="row">
                                <div class="col-12 col-sm-8">
                                    <div class="row">
                                        <div class="btn-group" role="group" aria-label="First group">
                                            <button type="button"
                                                    class="btn btn-light">{{ partido.getVisitante().NOMBRE }}</button>
                                            <button type="button" class="btn btn-light">
                                                <img src="data:image/jpeg;base64,{{ base64_encode(partido.getVisitante().LOGO) }}"
                                                     class="img-responsive" width="25"/>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="row">
                                        <div class="form-group">
                                            {% if pronosticos.permitidoModificar(partido.FECHA) %}
                                                {{ numeric_field("VISITANTE_" ~ partido.PARTIDOS_ID ~"_" ~ partido.VISITANTE, 'min':"0", 'max':"100", 'class':"form-control text-center", "required":"required") }}
                                            {% else %}
                                                {{ text_field("VISITANTE_" ~ partido.PARTIDOS_ID ~"_" ~ partido.VISITANTE, 'readonly':"readonly", 'class':"form-control text-center", 'id':"", 'name':"") }}
                                            {% endif %}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {% if loop.last %}
                <div class="col-md-12">
                    <div class="tile">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel text-center">
                                <button type="submit" class="btn btn-success">Registrar pronósticos
                                    del {{ fechaSeleccionada }}</button>
                            </div>
                        </div>
                    </div>
                </div>
        {% endif %}
    {% endfor %}
</div>
{{ endform() }}