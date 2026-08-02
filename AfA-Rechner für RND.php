<?php
/**
 * AfA-Rechner Restnutzungsdauer als Shortcode
 *
 * Einbau ueber WPCode:
 *   Code-Snippets -> Snippet hinzufuegen -> "Deinen individuellen Code hinzufuegen"
 *   Code-Typ: PHP-Snippet
 *   Diesen gesamten Inhalt einfuegen, inklusive der Zeile <?php
 *   Einfuegen: Automatisch, Ueberall ausfuehren
 *   Schalter auf Aktiv, dann speichern
 *
 * Verwendung im Beitrag:  [bk_rnd_rechner]
 *
 * Warum ein Shortcode und kein Code-Block: WordPress entfernt div-Container
 * aus dem Beitragsinhalt. Ein Shortcode liefert das Markup erst nach den
 * Content-Filtern aus, deshalb bleibt die Struktur vollstaendig erhalten.
 *
 * Das CSS steht im Quick CSS. Das JavaScript wird hier mit ausgeliefert und
 * nur geladen, wenn der Shortcode auf der Seite tatsaechlich vorkommt.
 */

add_shortcode( 'bk_rnd_rechner', 'bk_rnd_rechner_ausgabe' );

function bk_rnd_rechner_ausgabe() {

	$markup = <<<'HTML'
<div class="bk-rnd-wrap">
  <div class="bk-rnd-card">
    <h3 class="bk-rnd-titel">Was bringt eine kürzere Restnutzungsdauer wirklich?</h3>
    <p class="bk-rnd-sub">Der Rechner zeigt beide Seiten: den jährlichen Vorteil und das
       Risiko, wenn das Finanzamt die Kaufpreisaufteilung kürzt.</p>
    <div class="bk-rnd-grid">
      <div class="bk-rnd-feldgruppe">
        <label class="bk-rnd-label" for="bk-rnd-gebaeude">Gebäudeanteil laut Kaufvertrag
          <span class="bk-rnd-hint">Kaufpreis abzüglich Grund und Boden, in EUR</span>
        </label>
        <input class="bk-rnd-feld" type="number" id="bk-rnd-gebaeude" value="500000" min="0" step="10000">
      </div>
      <div class="bk-rnd-feldgruppe">
        <label class="bk-rnd-label" for="bk-rnd-regulaer">Gesetzlicher AfA-Satz
          <span class="bk-rnd-hint">richtet sich nach dem Fertigstellungsjahr</span>
        </label>
        <select class="bk-rnd-feld" id="bk-rnd-regulaer">
          <option value="2">2 % — Fertigstellung 1925 bis 2022</option>
          <option value="2.5">2,5 % — Fertigstellung vor 1925</option>
          <option value="3">3 % — Fertigstellung ab 2023</option>
        </select>
      </div>
      <div class="bk-rnd-feldgruppe">
        <label class="bk-rnd-label" for="bk-rnd-jahre">Restnutzungsdauer laut Gutachten
          <span class="bk-rnd-hint">in Jahren</span>
        </label>
        <input class="bk-rnd-feld" type="number" id="bk-rnd-jahre" value="25" min="1" max="50" step="1">
      </div>
      <div class="bk-rnd-feldgruppe">
        <label class="bk-rnd-label" for="bk-rnd-satz">Grenzsteuersatz Einkommensteuer
          <span class="bk-rnd-hint">in Prozent, ohne Zuschläge — steht im Steuerbescheid</span>
        </label>
        <input class="bk-rnd-feld" type="number" id="bk-rnd-satz" value="42" min="0" max="45" step="1">
      </div>
      <div class="bk-rnd-feldgruppe">
        <label class="bk-rnd-label" for="bk-rnd-kirche">Kirchensteuer
          <span class="bk-rnd-hint">8 % in Bayern und Baden-Württemberg, sonst 9 %</span>
        </label>
        <select class="bk-rnd-feld" id="bk-rnd-kirche">
          <option value="0">keine</option>
          <option value="0.08">8 %</option>
          <option value="0.09" selected>9 %</option>
        </select>
      </div>
      <div class="bk-rnd-check">
        <input class="bk-rnd-box" type="checkbox" id="bk-rnd-soli" checked>
        <label class="bk-rnd-label" for="bk-rnd-soli">Solidaritätszuschlag fällt an</label>
      </div>
      <div class="bk-rnd-voll">
        <label class="bk-rnd-label" for="bk-rnd-kuerzung">Angenommene Kürzung des Gebäudeanteils
          <span class="bk-rnd-hint">Szenario: um wie viel Prozent das Finanzamt die
          Aufteilung nach unten korrigiert. 0 bedeutet, es bleibt bei der Aufteilung
          aus dem Kaufvertrag.</span>
        </label>
        <input class="bk-rnd-feld" type="number" id="bk-rnd-kuerzung" value="0" min="0" max="80" step="5">
      </div>
    </div>
    <p class="bk-rnd-satzinfo" id="bk-rnd-satzinfo" aria-live="polite">–</p>
    <div class="bk-rnd-block bk-rnd-vorteil">
      <h4>Der Vorteil</h4>
      <div class="bk-rnd-row"><span>AfA ohne Gutachten</span><span id="bk-rnd-afa-alt">–</span></div>
      <div class="bk-rnd-row"><span>AfA mit verkürzter Restnutzungsdauer</span><span id="bk-rnd-afa-neu">–</span></div>
      <div class="bk-rnd-row"><span>Zusätzliche Abschreibung pro Jahr</span><span id="bk-rnd-diff">–</span></div>
      <div class="bk-rnd-row"><span>Steuerersparnis pro Jahr</span><span id="bk-rnd-ersparnis">–</span></div>
      <span class="bk-rnd-gross" id="bk-rnd-vorteil-gesamt" aria-live="polite">–</span>
    </div>
    <div class="bk-rnd-block bk-rnd-risiko">
      <h4>Das Risiko</h4>
      <div class="bk-rnd-row"><span>Gebäudeanteil nach Kürzung</span><span id="bk-rnd-geb-neu">–</span></div>
      <div class="bk-rnd-row"><span>AfA-Volumen ursprünglich</span><span id="bk-rnd-vol-alt">–</span></div>
      <div class="bk-rnd-row"><span>AfA-Volumen nach Kürzung</span><span id="bk-rnd-vol-neu">–</span></div>
      <div class="bk-rnd-row"><span>Dauerhaft verlorene Steuerersparnis</span><span id="bk-rnd-verlust-steuer">–</span></div>
      <span class="bk-rnd-gross" id="bk-rnd-verlust" aria-live="polite">–</span>
    </div>
    <p class="bk-rnd-fazit" id="bk-rnd-fazit" aria-live="polite">–</p>
    <p class="bk-rnd-disclaimer">Vereinfachtes Modell zur Orientierung, keine steuerliche
      Beratung. Der kombinierte Grenzsteuersatz wird näherungsweise ermittelt; der
      Sonderausgabenabzug der Kirchensteuer ist berücksichtigt. Der Solidaritätszuschlag
      fällt seit 2021 erst oberhalb einer Freigrenze an — prüfen Sie im Steuerbescheid, ob
      er bei Ihnen erhoben wird. Nicht berücksichtigt sind Änderungen des Steuersatzes über
      die Laufzeit, Zinseffekte durch die frühere Liquidität sowie die Kosten des
      Gutachtens. Die tatsächliche Kürzung des Gebäudeanteils hängt vom
      Einzelfall ab und lässt sich nicht vorhersagen.</p>
  </div>
</div>
HTML;

	$js = <<<'JS'
(function(){
  var eur = new Intl.NumberFormat('de-DE',{style:'currency',currency:'EUR',maximumFractionDigits:0});
  var pct = function(z){return z.toLocaleString('de-DE',{maximumFractionDigits:1})+' %';};
  var ids = ['bk-rnd-gebaeude','bk-rnd-regulaer','bk-rnd-jahre','bk-rnd-satz',
             'bk-rnd-kirche','bk-rnd-soli','bk-rnd-kuerzung'];
  var setz = function(id,wert){var e=document.getElementById(id); if(e) e.textContent=wert;};
  function rechne(){
    var gebaeude = Math.max(0, parseFloat(document.getElementById('bk-rnd-gebaeude').value)||0);
    var regulaer = parseFloat(document.getElementById('bk-rnd-regulaer').value)||2;
    var jahre    = Math.max(1, parseFloat(document.getElementById('bk-rnd-jahre').value)||1);
    var estSatz  = Math.max(0, parseFloat(document.getElementById('bk-rnd-satz').value)||0)/100;
    var kirche   = parseFloat(document.getElementById('bk-rnd-kirche').value)||0;
    var soli     = document.getElementById('bk-rnd-soli').checked ? 0.055 : 0;
    // Kombinierter Grenzsteuersatz: Zuschlaege auf die Einkommensteuer, abzueglich
    // der Ersparnis daraus, dass die Kirchensteuer als Sonderausgabe abziehbar ist.
    // Vereinfachte Naeherung, Abweichung zur exakten Berechnung unter 0,1 Prozentpunkten.
    var satz = estSatz*(1+soli+kirche) - estSatz*estSatz*kirche;
    var kuerzung = Math.max(0, parseFloat(document.getElementById('bk-rnd-kuerzung').value)||0)/100;
    // AfA-Satz bei verkuerzter Nutzungsdauer ist der Kehrwert der Restnutzungsdauer
    var satzNeu = 100/jahre;
    var gebaeudeNeu = gebaeude * (1-kuerzung);
    // Der Vorteil rechnet mit dem gekuerzten Gebaeudeanteil, denn das ist die
    // Bemessungsgrundlage, die am Ende tatsaechlich gilt.
    var afaAlt = gebaeude * regulaer/100;
    var afaNeu = gebaeudeNeu * satzNeu/100;
    var diff   = afaNeu - afaAlt;
    var teile = [];
    if (soli)   teile.push('Solidaritätszuschlag');
    if (kirche) teile.push('Kirchensteuer ' + pct(kirche*100));
    setz('bk-rnd-satzinfo', '');
    var si = document.getElementById('bk-rnd-satzinfo');
    if (si) si.innerHTML = 'Kombinierter Grenzsteuersatz: <strong>' + pct(satz*100) + '</strong>'
      + (teile.length ? ' — Einkommensteuer ' + pct(estSatz*100) + ' zuzüglich ' + teile.join(' und ')
                      : ' — nur Einkommensteuer, ohne Zuschläge');
    setz('bk-rnd-afa-alt', eur.format(afaAlt) + ' (' + pct(regulaer) + ')');
    setz('bk-rnd-afa-neu', eur.format(afaNeu) + ' (' + pct(satzNeu) + ')');
    setz('bk-rnd-diff',    (diff>=0?'+ ':'') + eur.format(diff));
    setz('bk-rnd-ersparnis', (diff>=0?'+ ':'') + eur.format(diff*satz));
    setz('bk-rnd-vorteil-gesamt',
      diff>=0 ? 'Rund ' + eur.format(diff*satz) + ' mehr pro Jahr, für ' + jahre + ' Jahre'
              : 'Kein Vorteil: die Abschreibung sinkt um ' + eur.format(Math.abs(diff)) + ' pro Jahr');
    // Das AfA-Volumen ist der Betrag, der ueber die gesamte Laufzeit abgeschrieben wird.
    // Eine Kuerzung des Gebaeudeanteils verringert ihn dauerhaft.
    var verlust = gebaeude - gebaeudeNeu;
    setz('bk-rnd-geb-neu',  eur.format(gebaeudeNeu));
    setz('bk-rnd-vol-alt',  eur.format(gebaeude));
    setz('bk-rnd-vol-neu',  eur.format(gebaeudeNeu));
    setz('bk-rnd-verlust-steuer', eur.format(verlust*satz));
    setz('bk-rnd-verlust',
      verlust>0 ? eur.format(verlust) + ' AfA-Volumen fallen dauerhaft weg'
                : 'Keine Kürzung angenommen — das Risiko ist damit nicht ausgeschlossen');
    // Fazit: vergleicht den Gesamtvorteil ueber die Laufzeit mit dem dauerhaften Verlust
    var vorteilGesamt = diff*satz*jahre;
    var verlustSteuer = verlust*satz;
    var f;
    if (verlust === 0) {
      f = 'Ohne Kürzung ergibt sich über ' + jahre + ' Jahre eine Ersparnis von rund <strong>'
        + eur.format(vorteilGesamt) + '</strong>. Setzen Sie oben eine mögliche Kürzung ein, '
        + 'um zu sehen, wie schnell dieser Vorteil aufgezehrt wird.';
    } else if (vorteilGesamt > verlustSteuer) {
      f = 'Der Vorteil über ' + jahre + ' Jahre liegt bei rund <strong>' + eur.format(vorteilGesamt)
        + '</strong>, der dauerhafte Verlust bei <strong>' + eur.format(verlustSteuer)
        + '</strong>. Unter diesen Annahmen bleibt ein Plus — allerdings erst nach vollem '
        + 'Ablauf der verkürzten Nutzungsdauer.';
    } else {
      f = 'Der Vorteil über ' + jahre + ' Jahre liegt bei rund <strong>' + eur.format(vorteilGesamt)
        + '</strong>, der dauerhafte Verlust bei <strong>' + eur.format(verlustSteuer)
        + '</strong>. Unter diesen Annahmen kostet die kürzere Restnutzungsdauer mehr, '
        + 'als sie einbringt.';
    }
    var fz = document.getElementById('bk-rnd-fazit');
    if (fz) fz.innerHTML = f;
  }
  ids.forEach(function(id){
    var e = document.getElementById(id);
    if (e) { e.addEventListener('input', rechne); e.addEventListener('change', rechne); }
  });
  rechne();
})();
JS;

	// Skript ans Markup haengen; laeuft nur, wenn der Shortcode verwendet wird
	return $markup . '<script>' . $js . '</script>';
}
