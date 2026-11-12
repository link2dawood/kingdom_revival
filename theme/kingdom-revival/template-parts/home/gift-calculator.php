<?php
/**
 * Home: "Your gift at work" gift calculator (replaces the home page's amount cards; the cards, template-parts/home/gift.php,
 * stay on ministry and What We Do pages). Slider $10–$1,500 in $5 steps, quick amounts, and what the gift could provide.
 * Driven by assets/js/front.js (Gift calculator); styles in assets/css/front.css (.krm-calc). Without JavaScript the
 * server-rendered $100 state and its Give link still work.
 *
 * "Give" opens Donate the same way as the hero card's button: ?amount=N&frequency=One-time#give (krm_home_donate_href),
 * with no ministry, so the gift goes where it is needed most.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_min     = 10;
$krm_max     = 1500;
$krm_default = 100;
$krm_chips   = array( 25, 50, 100, 250, 500 );
// Rows: [ dollars per item (0 = no number, always on), label (plural, or an array( singular, plural ): the singular is shown when the number is exactly 1), icon path ].
$krm_rows = array(
	array( 10, array( 'Urdu Bible for a family or a new believer', 'Urdu Bibles for families or new believers' ), 'M6 3h11a2 2 0 0 1 2 2v14a1 1 0 0 1-1 1H7a3 3 0 0 1-3-3V5a2 2 0 0 1 2-2zm0 14a1 1 0 0 0 1 1h10v-2H7a1 1 0 0 0-1 1zm5-11v2H9v2h2v5h2v-5h2V8h-2V6z' ),
	array( 45, array( 'family medical camp visit', 'family medical camp visits' ), 'M10 3h4v7h7v4h-7v7h-4v-7H3v-4h7z' ),
	array( 40, array( 'child’s schooling (fees, books and uniform)', 'children’s schooling (fees, books and uniform)' ), 'M12 3 1 9l11 6 9-4.9V17h2V9zM5 13.2v4L12 21l7-3.8v-4L12 17z' ),
	array( 0, 'Helps free a family from kiln debt', 'M12 2a5 5 0 0 0-5 5v3H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2H9V7a3 3 0 0 1 5.8-1.1l1.9-.7A5 5 0 0 0 12 2zm0 11a2 2 0 0 1 1 3.7V19h-2v-2.3A2 2 0 0 1 12 13z' ),
);
$krm_pct = ( $krm_default - $krm_min ) / ( $krm_max - $krm_min ) * 100;
?>
<!-- Your gift at work: gift calculator -->
<section class="krm-calc" aria-labelledby="gift-h" data-krm-calc data-min="<?php echo (int) $krm_min; ?>" data-max="<?php echo (int) $krm_max; ?>">
<div class="krm-calc__head">
<h2 id="gift-h" class="krm-calc__title"><?php krm_h( 'calc.heading', 'home' ); ?></h2>
<p class="krm-calc__lead"><?php krm_t( 'calc.text', 'home' ); ?></p>
</div>
<div class="krm-calc__panel">
<div class="krm-calc__controls">
<label class="krm-calc__label" for="krm-calc-range"><?php esc_html_e( 'Your gift amount', 'kingdom-revival' ); ?></label>
<p class="krm-calc__amount" aria-hidden="true" data-krm-calc-amount>$<?php echo esc_html( number_format( $krm_default ) ); ?></p>
<input class="krm-calc__range" id="krm-calc-range" type="range" min="<?php echo (int) $krm_min; ?>" max="<?php echo (int) $krm_max; ?>" step="5" value="<?php echo (int) $krm_default; ?>" aria-valuetext="$<?php echo esc_attr( number_format( $krm_default ) ); ?>" style="--p: <?php echo esc_attr( round( $krm_pct, 3 ) ); ?>%" data-krm-calc-range>
<div class="krm-calc__scale" aria-hidden="true"><span>$<?php echo (int) $krm_min; ?></span><span>$<?php echo esc_html( number_format( $krm_max ) ); ?></span></div>
<div class="krm-calc__chips" role="group" aria-label="<?php esc_attr_e( 'Quick amounts', 'kingdom-revival' ); ?>">
<?php foreach ( $krm_chips as $krm_chip ) : ?>
<button type="button" class="krm-calc__chip" data-krm-calc-chip="<?php echo (int) $krm_chip; ?>" aria-pressed="<?php echo $krm_chip === $krm_default ? 'true' : 'false'; ?>">$<?php echo (int) $krm_chip; ?></button>
<?php endforeach; ?>
</div>
<a class="k-y krm-calc__give" href="<?php echo esc_url( krm_home_donate_href( '$' . $krm_default ) ); ?>" data-krm-calc-give><?php esc_html_e( 'Give', 'kingdom-revival' ); ?> <span data-krm-calc-give-amount>$<?php echo esc_html( number_format( $krm_default ) ); ?></span></a>
<p class="krm-calc__sr" aria-live="polite" aria-atomic="true" data-krm-calc-live></p>
</div>
<div class="krm-calc__impact">
<h3 class="krm-calc__list-title"><?php krm_t( 'calc.list_title', 'home' ); ?></h3>
<ul class="krm-calc__list">
<?php
foreach ( $krm_rows as $krm_row ) :
	list( $krm_unit, $krm_label, $krm_icon ) = $krm_row;
	$krm_n = $krm_unit ? (int) floor( $krm_default / $krm_unit ) : 1;
	$krm_forms = is_array( $krm_label ) ? $krm_label : array( $krm_label, $krm_label );
	$krm_label = 1 === $krm_n ? $krm_forms[0] : $krm_forms[1];
	?>
<li class="krm-calc__row<?php echo $krm_n < 1 ? ' is-locked' : ''; ?>"<?php echo $krm_unit ? ' data-unit="' . (int) $krm_unit . '"' : ''; ?>>
<span class="krm-calc__icon" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24"><path d="<?php echo esc_attr( $krm_icon ); ?>"></path></svg></span>
<?php if ( $krm_unit ) : ?>
<span class="krm-calc__num" data-krm-calc-num><?php echo (int) $krm_n; ?></span>
<?php else : ?>
<span class="krm-calc__num krm-calc__num--check" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 24 24"><path d="m9.5 16.2-4.2-4.2-1.4 1.4 5.6 5.6 11-11-1.4-1.4z"></path></svg></span>
<?php endif; ?>
<span class="krm-calc__text"><span class="krm-calc__what"<?php echo $krm_forms[0] !== $krm_forms[1] ? ' data-one="' . esc_attr( $krm_forms[0] ) . '" data-many="' . esc_attr( $krm_forms[1] ) . '"' : ''; ?>><?php echo esc_html( $krm_label ); ?></span><?php if ( $krm_unit ) : ?><span class="krm-calc__unlock" data-krm-calc-unlock<?php echo $krm_n < 1 ? '' : ' hidden'; ?>>Add $<?php echo (int) max( 0, $krm_unit - $krm_default ); ?> more to unlock</span><?php endif; ?></span>
</li>
<?php endforeach; ?>
</ul>
<p class="krm-calc__note"><?php krm_t( 'calc.note', 'home' ); ?></p>
</div>
</div>
</section>
