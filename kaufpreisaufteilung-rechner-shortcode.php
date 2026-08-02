<?php
/**
 * Shortcode: [bk_kaufpreisaufteilung_rechner]
 * Geführter Folgerechner zur BMF-Arbeitshilfe Kaufpreisaufteilung.
 */

if (!defined('ABSPATH')) {
    exit;
}

function bk_kaufpreisaufteilung_rechner_shortcode(): string
{
    static $instance = 0;
    $instance++;
    $id = 'bk-kpa-' . $instance;

    ob_start();
    ?>
    <section class="bk-kpa" id="<?php echo esc_attr($id); ?>" aria-labelledby="<?php echo esc_attr($id); ?>-title">
        <div class="bk-kpa__head">
            <p class="bk-kpa__eyebrow">Geführter Wohnimmobilienrechner</p>
            <h2 id="<?php echo esc_attr($id); ?>-title" style="margin-left:0 !important; margin-top:0 !important;">Kaufpreisaufteilung mit 20-%-Orientierung</h2><!-- Inline-Style als Notanker: Das Child-Theme haengt H2 auf Beitragsseiten -60px nach links; Stylesheet-Korrekturen griffen hier nicht durchgaengig. Inline mit !important schlaegt jede externe Regel. Titel gekuerzt (Prozent -> %), damit er einzeilig bleibt. -->
            <p>Übernehmen Sie zunächst die beiden Kaufpreisanteile aus der aktuellen BMF-Arbeitshilfe. Der Rechner zeigt anschließend, wie sich eine Reduzierung des dort ermittelten Bodenanteils auf die Aufteilung und die jährliche Gebäude-AfA auswirkt.</p>
        </div>

        <div class="bk-kpa__notice">
            <strong>Bitte zuerst amtlich rechnen:</strong>
            Dieser Rechner ist an die BMF-Arbeitshilfe angelehnt, bildet sie aber nicht nach und ersetzt weder deren vollständige Bearbeitung noch eine Bewertung des Einzelfalls.<br>
            <a href="https://www.bundesfinanzministerium.de/Datenportal/Daten/frei-nutzbare-produkte/Anwendungen/Kaufpreisaufteilung-Grundstuecke/Kaufpreisaufteilung-Grundstuecke.html" target="_blank" rel="noopener">Aktuelle BMF-Arbeitshilfe öffnen</a>
        </div>

        <form class="bk-kpa__form" novalidate>
            <div class="bk-kpa__step">
                <span class="bk-kpa__step-number" aria-hidden="true">1</span>
                <div>
                    <h3>Ergebnisse der BMF-Arbeitshilfe eintragen</h3>
                    <p>Verwenden Sie die Beträge aus der Spalte „Kaufpreisanteile“. Beide Werte müssen zusammen den Anschaffungskosten entsprechen.</p>
                </div>
            </div>

            <div class="bk-kpa__fields">
                <div class="bk-kpa__field">
                    <label for="<?php echo esc_attr($id); ?>-gesamt">Anschaffungskosten einschließlich Nebenkosten</label>
                    <div class="bk-kpa__input-wrap">
                        <input id="<?php echo esc_attr($id); ?>-gesamt" name="gesamt" type="number" min="1" step="100" value="500000" inputmode="decimal" required>
                        <span>EUR</span>
                    </div>
                </div>

                <div class="bk-kpa__field">
                    <label for="<?php echo esc_attr($id); ?>-boden">BMF-Kaufpreisanteil Grund und Boden</label>
                    <div class="bk-kpa__input-wrap">
                        <input id="<?php echo esc_attr($id); ?>-boden" name="boden" type="number" min="0" step="100" value="160000" inputmode="decimal" required>
                        <span>EUR</span>
                    </div>
                </div>

                <div class="bk-kpa__field">
                    <label for="<?php echo esc_attr($id); ?>-gebaeude">BMF-Kaufpreisanteil Gebäude</label>
                    <div class="bk-kpa__input-wrap">
                        <input id="<?php echo esc_attr($id); ?>-gebaeude" name="gebaeude" type="number" min="0" step="100" value="340000" inputmode="decimal" required>
                        <span>EUR</span>
                    </div>
                </div>
            </div>

            <div class="bk-kpa__step">
                <span class="bk-kpa__step-number" aria-hidden="true">2</span>
                <div>
                    <h3>Orientierungswert wählen</h3>
                    <p>Der gewählte Prozentsatz wird vom BMF-Bodenanteil abgezogen und in gleicher Höhe dem Gebäudeanteil zugerechnet.</p>
                </div>
            </div>

            <div class="bk-kpa__fields bk-kpa__fields--two">
                <div class="bk-kpa__field bk-kpa__field--range">
                    <label for="<?php echo esc_attr($id); ?>-abschlag">Reduzierung des Bodenanteils: <output for="<?php echo esc_attr($id); ?>-abschlag" data-role="abschlag-label">20 %</output></label>
                    <input id="<?php echo esc_attr($id); ?>-abschlag" name="abschlag" type="range" min="0" max="20" step="1" value="20">
                    <div class="bk-kpa__range-labels" aria-hidden="true"><span>0 %</span><span>20 %</span></div>
                </div>

                <div class="bk-kpa__field">
                    <label for="<?php echo esc_attr($id); ?>-afa">AfA-Satz für die Vergleichsrechnung</label>
                    <select id="<?php echo esc_attr($id); ?>-afa" name="afa">
                        <option value="2">2,0 %</option>
                        <option value="2.5">2,5 %</option>
                        <option value="3">3,0 %</option>
                    </select>
                </div>
            </div>

            <p class="bk-kpa__error" data-role="error" role="alert" hidden></p>
        </form>

        <div class="bk-kpa__results" data-role="results" aria-live="polite">
            <div class="bk-kpa__result-card">
                <p class="bk-kpa__result-kicker">Ausgangswert laut BMF</p>
                <h3>Typisierte Kaufpreisaufteilung</h3>
                <dl>
                    <div><dt>Grund und Boden</dt><dd data-role="bmf-boden">160.000 EUR · 32,0 %</dd></div>
                    <div><dt>Gebäude</dt><dd data-role="bmf-gebaeude">340.000 EUR · 68,0 %</dd></div>
                    <div><dt>Jährliche Gebäude-AfA</dt><dd data-role="bmf-afa">6.800 EUR</dd></div>
                </dl>
            </div>

            <div class="bk-kpa__result-card bk-kpa__result-card--accent">
                <p class="bk-kpa__result-kicker">Vertragliche Orientierung</p>
                <h3 data-role="orientierung-title">20 % weniger Bodenanteil</h3>
                <dl>
                    <div><dt>Grund und Boden</dt><dd data-role="neu-boden">128.000 EUR · 25,6 %</dd></div>
                    <div><dt>Gebäude</dt><dd data-role="neu-gebaeude">372.000 EUR · 74,4 %</dd></div>
                    <div><dt>Jährliche Gebäude-AfA</dt><dd data-role="neu-afa">7.440 EUR</dd></div>
                </dl>
            </div>
        </div>

        <div class="bk-kpa__balance" aria-live="polite">
            <div>
                <span>Der rechnerische Vorteil</span>
                <strong data-role="vorteil">+640 EUR AfA pro Jahr</strong>
            </div>
            <div>
                <span>Das steuerliche Risiko</span>
                <strong>Keine feste 20-Prozent-Freigrenze</strong>
            </div>
            <p data-role="fazit">Die Verschiebung ist nur eine Orientierung. Sie muss zu den realen Wertverhältnissen, zum Kaufvertrag und zu weiteren Gutachten – insbesondere zur Restnutzungsdauer – passen.</p>
        </div>

        <details class="bk-kpa__details">
            <summary>Was der Rechner nicht berücksichtigt</summary>
            <p>Das Modell prüft weder Bodenrichtwert, Miteigentumsanteil, Baujahr, Modernisierungen, Vergleichsfaktoren, Mieten, Liegenschaftszinssatz noch besondere objektspezifische Grundstücksmerkmale. Die AfA-Anzeige ist eine vereinfachte Jahresrechnung und berücksichtigt im Anschaffungsjahr keine zeitanteilige Kürzung. Der Rechner trifft keine Aussage darüber, ob das Finanzamt eine konkrete Aufteilung anerkennt.</p>
        </details>

        <p class="bk-kpa__disclaimer"><strong>Hinweis:</strong> Vereinfachte Orientierung, keine steuerliche oder gutachterliche Beratung. Maßgeblich sind die aktuelle BMF-Arbeitshilfe, die tatsächlichen Objektdaten und die rechtliche Würdigung des Einzelfalls.</p>
    </section>

    <script>
    (function () {
        'use strict';
        var root = document.getElementById(<?php echo wp_json_encode($id); ?>);
        if (!root || root.dataset.initialized === 'true') return;
        root.dataset.initialized = 'true';

        var form = root.querySelector('.bk-kpa__form');
        var totalInput = form.elements.gesamt;
        var landInput = form.elements.boden;
        var buildingInput = form.elements.gebaeude;
        var reductionInput = form.elements.abschlag;
        var afaInput = form.elements.afa;
        var error = root.querySelector('[data-role="error"]');
        var results = root.querySelector('[data-role="results"]');
        var money = new Intl.NumberFormat('de-DE', { maximumFractionDigits: 0 });
        var percent = new Intl.NumberFormat('de-DE', { minimumFractionDigits: 1, maximumFractionDigits: 1 });

        function setText(role, value) {
            var element = root.querySelector('[data-role="' + role + '"]');
            if (element) element.textContent = value;
        }

        function eur(value) {
            return money.format(Math.round(value)) + ' EUR';
        }

        function allocation(value, total) {
            return eur(value) + ' · ' + percent.format(value / total * 100) + ' %';
        }

        function calculate() {
            var total = Number(totalInput.value);
            var land = Number(landInput.value);
            var building = Number(buildingInput.value);
            var reduction = Number(reductionInput.value);
            var afaRate = Number(afaInput.value) / 100;
            var sum = land + building;
            var tolerance = 1;

            setText('abschlag-label', percent.format(reduction) + ' %');

            if (![total, land, building, reduction, afaRate].every(Number.isFinite) || total <= 0 || land < 0 || building < 0 || land === 0 || building === 0) {
                error.textContent = 'Bitte tragen Sie positive Beträge für Anschaffungskosten, Boden und Gebäude ein.';
                error.hidden = false;
                results.hidden = true;
                return;
            }

            if (Math.abs(sum - total) > tolerance) {
                error.textContent = 'Die BMF-Kaufpreisanteile ergeben zusammen ' + eur(sum) + ', die Anschaffungskosten aber ' + eur(total) + '. Bitte übernehmen Sie die beiden Werte aus der Spalte „Kaufpreisanteile“ und prüfen Sie die Eingaben.';
                error.hidden = false;
                results.hidden = true;
                return;
            }

            error.hidden = true;
            results.hidden = false;

            var shift = land * reduction / 100;
            var newLand = land - shift;
            var newBuilding = building + shift;
            var oldAfa = building * afaRate;
            var newAfa = newBuilding * afaRate;
            var advantage = newAfa - oldAfa;

            setText('bmf-boden', allocation(land, total));
            setText('bmf-gebaeude', allocation(building, total));
            setText('bmf-afa', eur(oldAfa));
            setText('orientierung-title', percent.format(reduction) + ' % weniger Bodenanteil');
            setText('neu-boden', allocation(newLand, total));
            setText('neu-gebaeude', allocation(newBuilding, total));
            setText('neu-afa', eur(newAfa));
            setText('vorteil', '+' + eur(advantage) + ' AfA pro Jahr');

            var conclusion = reduction === 0
                ? 'Ohne Verschiebung entspricht die Orientierung dem Ergebnis der BMF-Arbeitshilfe.'
                : 'Die Verschiebung ist nur eine Orientierung. Sie muss zu den realen Wertverhältnissen, zum Kaufvertrag und zu weiteren Gutachten – insbesondere zur Restnutzungsdauer – passen.';
            setText('fazit', conclusion);
        }

        form.addEventListener('input', calculate);
        form.addEventListener('change', calculate);
        calculate();
    }());
    </script>
    <?php
    return (string) ob_get_clean();
}

add_shortcode('bk_kaufpreisaufteilung_rechner', 'bk_kaufpreisaufteilung_rechner_shortcode');
