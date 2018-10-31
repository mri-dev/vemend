<? require "szallas_head.php"; ?>

<h1>Tisztelt <?=$name?>!</h1>

<?php if ($to_owner): ?>
	Új szállás ajánlatkérése érkezett a(z) <strong><?=$szallas['name']?></strong> szálláshelyére! Kérjük, hogy ellenőrizze az adatokat és igazolja vissza / véglegesítse az ajánlatkérőnek a foglalást az általa megadott kapcsolattartó elérhetőségek egyikén!
	<p>Kérjük, hogy a kapcsolatfelvétel során kizárólag az ajánlatkéréssel kapcsolatban keressék az érintett személy(eke)t!</p>
<?php else: ?>
	Köszönjük, hogy megtisztel bizalmával! A szállásfoglalással kapcsolatos igényét befogadtuk, hamarosan megkezdjük a feldolgozását! Az Ön által megadott kapcsolattartó elérhetőségei egyikén keresni fogjuk ajánlatunkkal!
<?php endif; ?>

<h2>Kiválasztott konfiguráció</h2>
<table class="if">
		<tbody>
			<tr>
				<th>Felnőttek száma</th>
				<td><?=$config['adults']?> db</td>
			</tr>
			<tr>
				<th>Gyermekek száma</th>
				<td><?=$config['children']?> db</td>
			</tr>
			<?php if ($config['children'] != 0): ?>
			<tr>
				<th>Gyermekek kora (év)</th>
				<td><?=implode(", ", $config['children_age'])?></td>
			</tr>
			<?php endif; ?>
			<tr>
				<th>Érkezés napja</th>
				<td><?=$config['datefrom']?></td>
			</tr>
			<tr>
				<th>Távozás napja</th>
				<td><?=$config['dateto']?></td>
			</tr>
			<tr>
				<th>Tartózkodási idő</th>
				<td><?=$config['nights']+1?> nap, <?=$config['nights']?> éjszaka</td>
			</tr>
			<tr>
				<th>Választott ellátás</th>
				<td><?=$config['room']['priceconfig']['ellatas_name']?></td>
			</tr>
			<tr>
				<th>Választott szoba</th>
				<td><strong><?=$config['room']['room']['name']?></strong><br><em><?=$config['room']['room']['leiras']?></em></td>
			</tr>
			<?php if ($config['kisallatot_hoz'] == 'true'): ?>
			<tr>
				<th>Kisállatot hoz?</th>
				<td>Igen</td>
			</tr>
			<?php endif; ?>
		</tbody>
</table>

<h2>Árkalkuláció összesítő</h2>
<table class="if">
		<tbody>
			<tr>
				<th>Idefenforgalmi adó</th>
				<td><?=$config['ifa_price']?> Ft*</td>
			</tr>
			<?php if ($config['kisallatot_hoz'] == 'true'): ?>
			<tr>
				<th>Kisállat felár</th>
				<td><?=$config['kisallat_dij']?> Ft</td>
			</tr>
			<?php endif; ?>
			<tr>
				<th>Szoba ár</th>
				<td><?=$config['room_prices']?> Ft**</td>
			</tr>
			<tr>
				<th>Összesített ár</th>
				<td><?=$config['total_price']?> Ft***</td>
			</tr>
		</tbody>
</table>
<br>
<small>A kalkuláció összesítőben található árak tájékoztató jellegűek, nem minősülnek konkrét ajánlatnak!</small> <br><br>
<small>* a várható idegenforgalmi adó mértéke.</small><br>
<small>** a kiválaszott szoba ára a kiválasztott időszakra az érkező vendégek számára.</small><br>
<small>*** a teljes ár a kiválasztott paraméterek alapján. Tájékoztató jellegű adat!</small><br>

<h2>Kapcsolattartó adatai</h2>
<table class="if">
		<tbody>
			<tr>
				<th>Név</th>
				<td><?=$config['order_contacts']['name']?></td>
			</tr>
			<tr>
				<th>E-mail cím</th>
				<td><?=$config['order_contacts']['email']?></td>
			</tr>
			<tr>
				<th>Telefonszám</th>
				<td><?=$config['order_contacts']['phone']?></td>
			</tr>
			<tr>
				<th>Megjegyzés</th>
				<td><?=($config['order_contacts']['comment']!='')?$config['order_contacts']['comment']:'--'?></td>
			</tr>
		</tbody>
</table>
<br><br>
<?php if ($to_owner): ?>
	Ajánlatkérés referencia ID (RFID): #<?=$rfid?>
<?php else: ?>
	Üdvözlettel,<br>
	<strong><?=$szallas['title']?></strong>,<br>
	<?=$szallas['cim']?>

<?php endif; ?>

<? require "szallas_footer.php"; ?>
