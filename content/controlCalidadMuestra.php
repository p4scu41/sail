<?php
$objHTML->startFieldset('Control de calidad de la muestra');

    $valoresCalidadMuestra = array( 'A'=>'Adecuada', 
                                    'I'=>'Inadecuada');

    $objHTML->label('Calidad de la muestra: ');
    $objHTML->inputRadio('calidadMuestra', $valoresCalidadMuestra, $objCalidad->calidadMuestra, array('class'=>'validate[required]'));
    echo '<br>(En caso de ser Inadecuada especifique las razones)<div id="div_calidadMuestra" class="calidadInadecuada">';
    $objHTML->inputCheckbox('Sin muestra', 'sinMuestra', 1, $objCalidad->sinMuestra);
    $objHTML->inputCheckbox('Sin elementos celulares', 'sinElemeCelu', 1, $objCalidad->sinElemeCelu);
    $objHTML->inputCheckbox('Abundantes eritrocitos', 'abunEritro', 1, $objCalidad->abunEritro);
    echo '<br>';
    $objHTML->inputText('Otros:', 'otrosCalidadMuestra', $objCalidad->otrosCalidadMuestra, array('size'=>40));
    
    echo '</div><br>';
    $objHTML->label('Calidad del frotis: ');
    $objHTML->inputRadio('calidadFrotis', $valoresCalidadMuestra, $objCalidad->calidadFrotis, array('class'=>'validate[required]'));
    echo '<br>(En caso de ser Inadecuada especifique las razones)<div id="div_calidadFrotis" class="calidadInadecuada">';
    $objSelects->SelectCatalogo('', 'calidadFrotisTipo', 'catCalidadFrotis', $objCalidad->calidadFrotisTipo);
    $objHTML->inputText('Otros:', 'otrosCalidadFrotis', $objCalidad->otrosCalidadFrotis, array('size'=>40));
    
    echo '</div><br>';
    $objHTML->label('Calidad de la tinción: ');
    $objHTML->inputRadio('calidadTincion', $valoresCalidadMuestra, $objCalidad->calidadTincion, array('class'=>'validate[required]'));
    echo '<br>(En caso de ser Inadecuada especifique las razones)<div id="div_calidadTincion" class="calidadInadecuada">';
    $objHTML->inputCheckbox('Cristales de fucsina', 'crisFucsi', 1, $objCalidad->crisFucsi);
    $objHTML->inputCheckbox('Precipitados de fucsina', 'preciFucsi', 1, $objCalidad->preciFucsi);
    $objHTML->inputCheckbox('Calentamiento excesivo', 'calenExce', 1, $objCalidad->calenExce);
    $objHTML->inputCheckbox('Decoloración insuficiente', 'decoInsufi', 1, $objCalidad->decoInsufi);
    echo '<br>';
    $objHTML->inputText('Otros:', 'otrosCalidadTincion', $objCalidad->otrosCalidadTincion, array('size'=>40));
    
    $valoresCalidadLectura = array( 'C'=>'Concordante', 
                                    'N'=>'No concordante');
    
    echo '</div><br>';
    $objHTML->label('Calidad de la lectura: ');
    $objHTML->inputRadio('calidadLectura', $valoresCalidadLectura, $objCalidad->calidadLectura, array('class'=>'validate[required]'));
    echo '<br>(En caso de ser No concordante especifique las razones)<div id="div_calidadLectura" class="calidadInadecuada">';
    $objHTML->inputCheckbox('Falsa Positiva', 'falPosi', 1, $objCalidad->falPosi);
    $objHTML->inputCheckbox('Falsa Negativa', 'falNega', 1, $objCalidad->falNega);
    $objHTML->inputCheckbox('Diferencia de mas de 2 cruces en IB', 'difMas2IB', 1, $objCalidad->difMas2IB);
    $objHTML->inputCheckbox('Diferencia de mas de 25% en IM', 'difMas25IM', 1, $objCalidad->difMas25IM);
    echo '<br>';
    $objHTML->inputText('Otros:', 'otrosCalidadLectura', $objCalidad->otrosCalidadLectura, array('size'=>40));
    
    echo '</div><br>';
    $objHTML->label('Calidad en la emisión de resultados:');
    $objHTML->inputRadio('calidadResultado', $valoresCalidadMuestra, $objCalidad->calidadResultado, array('class'=>'validate[required]'));
    echo '<br>(En caso de ser Inadecuada especifique las razones)<div id="div_calidadResultado" class="calidadInadecuada">';
    $objHTML->inputCheckbox('Solo con simbolos de cruz', 'soloSimbCruz', 1, $objCalidad->soloSimbCruz);
    $objHTML->inputCheckbox('Solo positivo o negativo', 'soloPosiNega', 1, $objCalidad->soloPosiNega);
    $objHTML->inputCheckbox('No emite IM', 'noEmiteIM', 1, $objCalidad->noEmiteIM);
    echo '<br>';
    $objHTML->inputText('Otros:', 'otrosCalidadResultado', $objCalidad->otrosCalidadResultado, array('size'=>40));
    
    echo '</div><br>';
    $objSelects->SelectCatalogo('Recomendación:', 'recomendacion', 'catRecomendacionCalidad', $objCalidad->recomendacion);
    
$objHTML->endFieldset();
?>