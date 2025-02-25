<?php
class Battle {
	
	public function procSim($post) {
		global $form;
		// Recivimos el formulario y procesamos
		if(isset($post['a1_v']) && (isset($post['a2_v1']) || isset($post['a2_v2']) || isset($post['a2_v3']) || isset($post['a2_v4']))) {
				$_POST['mytribe'] = $post['a1_v'];
				$target = array();
				if(isset($post['a2_v1'])) {
					array_push($target,1);
				}
				if(isset($post['a2_v2'])) {
					array_push($target,2);
				}
				if(isset($post['a2_v3'])) {
					array_push($target,3);
				}
				if(isset($post['a2_v4'])) {
					array_push($target,4);
				}
                if(isset($post['a2_v5'])) {
                    array_push($target,5);
                }
				$_POST['target'] = $target;
				if(isset($post['a1_1'])) {
					$sum = $sum2 = 0;
					for($i=1;$i<=10;$i++) {
						$var = $post['a1_'.$i];
						if(is_numeric($var)) {
							$sum += $var;
						}
					}
					if($sum > 0) {
						if(!isset($post['palast']) || $post['palast'] == "") {
							$post['palast'] = 0;
						}
						if(!isset($post['wall1']) || $post['wall1'] == "") {
							$post['wall1'] = 0;
						}
						if(!isset($post['wall2']) || $post['wall2'] == "") {
							$post['wall2'] = 0;
						}
						if(!isset($post['wall3']) || $post['wall3'] == "") {
							$post['wall3'] = 0;
						}
						if(!isset($post['wall4']) || $post['wall4'] == "") {
                            $post['wall4'] = 0;
                        }
						if(!isset($post['wall5']) || $post['wall5'] == "") {
                            $post['wall5'] = 0;
                        }
						$post['tribe'] = $target[0];
						$_POST['result'] = $this->simulate($post);
						$form->valuearray = $post;
					}
				}
		}
	}
	
	private function getBattleHero($uid) {
        global $database;
		$q = "SELECT * FROM ".TB_PREFIX."hero WHERE uid = ".$uid."";
        $heroarray = $database->query_return($q);
        
        $h_atk = $heroarray['power'];
        $h_di = $heroarray['offBonus'];
        $h_dc = $heroarray['defBonus'];
        $h_ob = 1 + 0.002 * $heroarray['power'];
        $h_db = 1 + 0.002 * $heroarray['power'];

        return array('heroid'=>$heroarray['heroid'],'power'=>$h_atk,'off'=>$h_di,'def'=>$h_dc,'power'=>$h_ob,'power'=>$h_db,'health'=>$heroarray['health']);
    }
	
	private function simulate($post) {
		// Establecemos los arrays con las unidades del atacante y defensor
		$attacker = array('u1'=>0,'u2'=>0,'u3'=>0,'u4'=>0,'u5'=>0,'u6'=>0,'u7'=>0,'u8'=>0,'u9'=>0,'u10'=>0,'u11'=>0,'u12'=>0,'u13'=>0,'u14'=>0,'u15'=>0,'u16'=>0,'u17'=>0,'u18'=>0,'u19'=>0,'u20'=>0,'u21'=>0,'u22'=>0,'u23'=>0,'u24'=>0,'u25'=>0,'u26'=>0,'u27'=>0,'u28'=>0,'u29'=>0,'u30'=>0,'u31'=>0,'u32'=>0,'u33'=>0,'u34'=>0,'u35'=>0,'u36'=>0,'u37'=>0,'u38'=>0,'u39'=>0,'u40'=>0,'u41'=>0,'u42'=>0,'u43'=>0,'u44'=>0,'u45'=>0,'u46'=>0,'u47'=>0,'u48'=>0,'u49'=>0,'u50'=>0);
		$start = ($post['a1_v']-1)*10+1;
		$att_ab = array('a1'=>0,'a2'=>0,'a3'=>0,'a4'=>0,'a5'=>0,'a6'=>0,'a7'=>0,'a8'=>0);
		$def_ab = array('b1'=>0,'b2'=>0,'b3'=>0,'b4'=>0,'b5'=>0,'b6'=>0,'b7'=>0,'b8'=>0);
		$index = 1;
		for($i=$start;$i<=($start+9);$i++) {
			$attacker['u'.$i] = $post['a1_'.$index];
			if($index <=8) {
				$att_ab['a'.$index] = $post['f1_'.$index];
			}
			$index += 1;
		}
		$defender = array();
		for($i=1;$i<=50;$i++) {
			if(isset($post['a2_'.$i]) && $post['a2_'.$i] != "") {
				$defender['u'.$i] = $post['a2_'.$i];
			}
			else {
				$defender['u'.$i] = 0;
			}
		}
		$deftribe = $post['tribe'];
		$wall = 0;
		switch($deftribe) {
			case 1:
			for($i=1;$i<=8;$i++) {
				$def_ab['b'.$i] = $post['f2_'.$i];
			}
			$wall = $post['wall1'];
			break;
			case 2:
			for($i=11;$i<=18;$i++) {
				$def_ab['b'.$i] = $post['f2_'.$i];
			}
			$wall = $post['wall2'];
			break;
			case 3:
			for($i=21;$i<=28;$i++) {
				$def_ab['b'.$i] = $post['f2_'.$i];
			}
			$wall = $post['wall3'];
			break;
            case 4:
            for($i=31;$i<=38;$i++) {
                $def_ab['b'.$i] = $post['f2_'.$i];
            }
            $wall = $post['wall4'];
            break;
            case 5:
            for($i=41;$i<=48;$i++) {
                $def_ab['b'.$i] = $post['f2_'.$i];
            }
            $wall = $post['wall5'];
            break;
		}
		if($post['kata'] == "") {
			$post['kata'] = 0;
		}

		// check scout

		$scout = 1;		
		for($i=$start;$i<=($start+9);$i++) {
			if($i == 4 || $i == 14 || $i == 23 || $i == 34 || $i == 44)
			{}
			else{
				if($attacker['u'.$i]>0) {
					$scout = 0;
					break;
				}
			}
		}

		if(!$scout){
			return $this->calculateBattle($attacker,$defender,$wall,$post['a1_v'],$deftribe,$post['palast'],$post['ew1'],$post['ew2'],$post['ktyp']+3,$def_ab,$att_ab,$post['kata'],$post['stonemason']);
		}else{
			return $this->calculateBattle($attacker,$defender,$wall,$post['a1_v'],$deftribe,$post['palast'],$post['ew1'],$post['ew2'],1,$def_ab,$att_ab,$post['kata'],$post['stonemason']);
		}
	}


	//1 raid 0 normal
	function calculateBattle($Attacker,$Defender,$def_wall,$att_tribe,$def_tribe,$residence,$attpop,$defpop,$type,$def_ab,$att_ab,$tblevel,$stonemason) {
		global $bid34,$database;
		// Definieer de array met de eenheden
		$calvary = array(4,5,6,15,16,23,24,25,26,35,36,45,46);
		$catapult = array(8,18,28,38,48);
		$rams = array(7,17,27,37,47);		
		$catp = $ram = 0;
		// Array om terug te keren met het resultaat van de berekening
		$result = array();
		$involve = 0;
		$winner = false;
		// bij 0 alle deelresultaten
		$cap = $ap = $dp = $cdp = $rap = $rdp = 0;
        
        //exit($type);

        if ($Attacker['hero'] != 0) $atkhero = $database->getHeroData2($Attacker['id']);
		if ($Defender['hero'] != 0) $defenderhero = $database->getHeroData3($Defender['id']);
		$DefendersAll = $database->getEnforceVillage($DefenderWref,0);
		if(!empty($DefendersAll)){
		foreach($DefendersAll as $defenders) {
		$fromvillage = $defenders['from'];
		$reinfowner = $database->getVillageField($fromvillage,"owner");
		$defhero[$fromvillage] = $database->getHeroData2($reinfowner);
		}
		}
        //
        // Berekenen het totaal aantal punten van Aanvaller
        //
        $start = ($att_tribe-1)*10+1;
        $end = ($att_tribe*10);
		if($att_tribe == 1){$atp == 100;}else{$atp ==80;}
		if($def_tribe == 1){$dtp == 100;}else{$dtp ==80;}
		$abcount = 1;

		if($type == 1){
			for($i=$start;$i<=$end;$i++) {
				global ${'u'.$i};
				if($abcount <= 8 && $att_ab['a'.$abcount] > 0) {
					$ap += (35 + ( 35 + 300 * ${'u'.$i}['pop'] / 7) * (pow(1.007, $att_ab['a'.$abcount]) - 1)) * $Attacker['u'.$i];
				}else {
					$ap += $Attacker['u'.$i] * 35;
				}
				$abcount +=1;
				$units['Att_unit'][$i] = $Attacker['u'.$i];
			}
			
			if($Attacker['hero']){
				$ap += (100+$atp*$atkhero['power']+$atkhero['itempower']) + $atkhero['offBonus'];
				$units['Att_unit']['hero'] = $Attacker['hero'];
			}
			
		}else{
			for($i=$start;$i<=$end;$i++) {
				global ${'u'.$i};
				
				$varAttackerUnit = $Attacker['u'.$i];
				if(!is_numeric($varAttackerUnit)) { $varAttackerUnit = 0; }

				$varAtk = ${'u'.$i}['atk'];

				if($abcount <= 8 && $att_ab['a'.$abcount] > 0) {
					if(in_array($i,$calvary)) {
						$cap += ($varAtk + ($varAtk + 300 * ${'u'.$i}['pop'] / 7) * (pow(1.007, $att_ab['a'.$abcount]) - 1)) * $varAttackerUnit;
					} else {
						$ap += ($varAtk + ($varAtk + 300 * ${'u'.$i}['pop'] / 7) * (pow(1.007, $att_ab['a'.$abcount]) - 1)) * $varAttackerUnit;
					}
				}else {
					if(in_array($i,$calvary)) {
						$cap += $varAttackerUnit * $varAtk;
					}else {
						$ap += $varAttackerUnit * $varAtk;
					}
				}
				
				$abcount +=1;
				// Punten van de catavult van de aanvaller
				if(in_array($i,$catapult)) {
					$catp += $varAttackerUnit;
				}
				 // Punten van de Rammen van de aanvaller
                if(in_array($i,$rams)) {
                    $ram += $varAttackerUnit;
                }
                $involve += $varAttackerUnit; 
                $units['Att_unit'][$i] = $varAttackerUnit;
            }
			
			if($Attacker['hero']) {
				$cap += (100+$atp*$atkhero['power']+$atkhero['itempower']) + ((100+$atp*$atkhero['power']+$atkhero['itempower']) + 300 * 6 / 7) * (pow(1.007, $atkhero['offBonus']) - 1);
				$ap += (100+$atp*$atkhero['power']+$atkhero['itempower']) + ((100+$atp*$atkhero['power']+$atkhero['itempower']) + 300 * 6 / 7) * (pow(1.007, $atkhero['offBonus']) - 1);
			}
			$involve += $Attacker['hero']; 
            $units['Att_unit']['hero'] = $Attacker['hero'];
        }

		//
		// Berekent het totaal aantal punten van de Defender
		//
        $start = ($def_tribe-1)*10+1;
        $end = ($def_tribe*10);
        $abcount = 1;
		if($type == 1){
			for($y=4;$y<=44;$y++) {
				if($y == 4 || $y == 14 || $y == 23 || $y == 34 || $y == 44){
					global ${'u'.$y};
					if($y >= $start && $y <= ($end-2) && $def_ab['b'.$abcount] > 0) {
						$dp +=  (20 + (20 + 300 * ${'u'.$y}['pop'] / 7) * (pow(1.007, $def_ab['b'.$y]) - 1)) * $Defender['u'.$y];
						$abcount +=1;
					} else {
						$dp += $Defender['u'.$y]*20;
					}
					$units['Def_unit'][$y] = $Defender['u'.$y];
				}
			}
			if($Defender['hero']){
				$dp +=  ((100+$dtp*(100+$dtp*(100+$dtp*$defenderhero['power']+$defenderhero['itempower'])+$defenderhero['itempower'])+$defenderhero['itempower']) + ((100+$dtp*(100+$dtp*$defenderhero['power']+$defenderhero['itempower'])+$defenderhero['itempower']) + 300 * 6 / 7) * (pow(1.007, $defenderhero['defBonus']) - 1));
			}
				$DefendersAll = $database->getEnforceVillage($DefenderWref,0);
				if(!empty($DefendersAll)){
				foreach($DefendersAll as $defenders) {
				$fromvillage = $defenders['from'];
				$dp +=  ((100+$dtp*(100+$dtp*(100+$dtp*$defhero[$fromvillage]['power']+$defhero[$fromvillage]['itempower'])+$defhero[$fromvillage]['itempower'])+$defhero[$fromvillage]['itempower']) + ((100+$dtp*(100+$dtp*$defhero[$fromvillage]['power']+$defhero[$fromvillage]['itempower'])+$defhero[$fromvillage]['itempower']) + 300 * 6 / 7) * (pow(1.007, $defhero[$fromvillage]['defBonus']) - 1));
				}
				}
			$units['Def_unit']['hero'] = $Defender['hero'];
		}else{
			for($y=1;$y<=50;$y++) {
				global ${'u'.$y};
				if($y >= $start && $y <= ($end-2) && $def_ab['b'.$abcount] > 0) {
					$dp +=  (${'u'.$y}['di'] + (${'u'.$y}['di'] + 300 * ${'u'.$y}['pop'] / 7) * (pow(1.007, $def_ab['b'.$y]) - 1)) * $Defender['u'.$y];
					$cdp += (${'u'.$y}['dc'] + (${'u'.$y}['dc'] + 300 * ${'u'.$y}['pop'] / 7) * (pow(1.007, $def_ab['b'.$y]) - 1)) * $Defender['u'.$y];
					$abcount +=1;
				}else {
					$dp += $Defender['u'.$y]*${'u'.$y}['di'];
					$cdp += $Defender['u'.$y]*${'u'.$y}['dc'];
				}
				$involve += $Defender['u'.$y]; 
				$units['Def_unit'][$y] = $Defender['u'.$y];
			}
			if($Defender['hero']){
				$dp +=  ((100+$dtp*(100+$dtp*$defenderhero['power']+$defenderhero['itempower'])+$defenderhero['itempower']) + ((100+$dtp*(100+$dtp*$defenderhero['power']+$defenderhero['itempower'])+$defenderhero['itempower']) + 300 * 6 / 7) * (pow(1.007, $defenderhero['defBonus']) - 1));
				$cdp += ((100+$dtp*(100+$dtp*$defenderhero['power']+$defenderhero['itempower'])+$defenderhero['itempower']) + ((100+$dtp*(100+$dtp*$defenderhero['power']+$defenderhero['itempower'])+$defenderhero['itempower']) + 300 * 6 / 7) * (pow(1.007, $defenderhero['defBonus']) - 1));
			}
				$DefendersAll = $database->getEnforceVillage($DefenderWref,0);
				if(!empty($DefendersAll)){
				foreach($DefendersAll as $defenders) {
				$fromvillage = $defenders['from'];
				$dp +=  ((100+$dtp*(100+$dtp*$defhero[$fromvillage]['power']+$defhero[$fromvillage]['itempower'])+$defhero[$fromvillage]['itempower']) + ((100+$dtp*(100+$dtp*$defhero[$fromvillage]['power']+$defhero[$fromvillage]['itempower'])+$defhero[$fromvillage]['itempower']) + 300 * 6 / 7) * (pow(1.007, $defhero[$fromvillage]['defBonus']) - 1));
				$cdp += ((100+$dtp*(100+$dtp*$defhero[$fromvillage]['power']+$defhero[$fromvillage]['itempower'])+$defhero[$fromvillage]['itempower']) + ((100+$dtp*(100+$dtp*$defhero[$fromvillage]['power']+$defhero[$fromvillage]['itempower'])+$defhero[$fromvillage]['itempower']) + 300 * 6 / 7) * (pow(1.007, $defhero[$fromvillage]['defBonus']) - 1));
				}
				}
			$involve += $Defender['hero']; 
			$units['Def_unit']['hero'] = $Defender['hero'];
		}

		//
		// Formule voor de berekening van de bonus verdedigingsmuur "en" Residence ";
		//		
		if($def_wall > 0) {
			// Stel de factor berekening voor de "Muur" als het type van de beschaving
			// Factor = 1030 Romeinse muur
			// Factor = 1020 Wall Germanen
			// Factor = 1025 Wall Galliers						
			$factor = ($def_tribe == 1)? 1.030 : (($def_tribe == 2)? 1.020 : 1.025);
			// Defense Infanterie = infanterie * Muur (%)
			$dp *= pow($factor,$def_wall);
			// Defensa Cavelerie = Cavelerie * Muur (%)
			$cdp *= pow($factor,$def_wall);

			// Berekening van de Basic defence bonus "Residence" 
			$dp += ((2*(pow($residence,2)))*(pow($factor,$def_wall)));
			$cdp += ((2*(pow($residence,2)))*(pow($factor,$def_wall)));
			if($def_tribe == 1){
			$bonus = 10;
			}else if($def_tribe == 2){
			$bonus = 6;
			}else if($def_tribe == 3){
			$bonus = 8;
			}
			$dp += $def_wall*$bonus;
			$cdp += $def_wall*$bonus;
		}
		else 
        {
			// Berekening van de Basic defence bonus "Residence" 			
			$dp += (2*(pow($residence,2)));
			$cdp += (2*(pow($residence,2)));
		}

		//
		// Formule voor het berekenen van punten aanvallers (Infanterie & Cavalry)
		//
			$rap = $ap+$cap;
		//
		// Formule voor de berekening van Defensive Punten
		//
			 if ($rap==0) {
                 $rdp = ($dp) + ($cdp) + 10;
			 }else{             
                 $rdp = ($dp * ($ap/$rap)) + ($cdp * ($cap/$rap)) + 10;
			 }
		//
		// En de Winnaar is....:
		//
		$result['Attack_points'] = $rap;
		$result['Defend_points'] = $rdp;

		$winner = ($rap > $rdp);

		$result['Winner'] = ($winner)? "attacker" : "defender";

		// Formule voor de berekening van de Moraal
		if($attpop > $defpop) {
			if($defpop > 0){
			if ($rap < $rdp) {
				$moralbonus = min(1.5, pow($attpop / $defpop, (0.2*($rap/$rdp))));
			}
			else {
				$moralbonus = min(1.5, pow($attpop / $defpop, 0.2));
			}
			}else{
			if ($rap < $rdp) {
				$moralbonus = min(1.5, pow($attpop, (0.2*($rap/$rdp))));
			}
			else {
				$moralbonus = min(1.5, pow($attpop, 0.2));
			}
			}
		}
		else {
			$moralbonus = 1.0;
		}

		if($involve >= 1000) {
			$Mfactor = round(2*(1.8592-pow($involve,0.015)),4);
		}
		else {
			$Mfactor = 1.5;
		}
		// Formule voor het berekenen verloren drives
		// $type = 1 Raid, 0 Normal
		if($type == 1){
		if($rap > 0){
			$holder = pow((($rdp*$moralbonus)/$rap),$Mfactor);
		}else{
			$holder = pow(($rdp*$moralbonus),$Mfactor);
		}
			$holder = $holder / (1 + $holder);
			// Attacker
			$result[1] = $holder;

			// Defender
			$result[2] = 0;
		}else if($type == 2){

		}
		else if($type == 4) {
			$holder = ($winner) ? pow((($rdp*$moralbonus)/$rap),$Mfactor) : pow(($rap/($rdp*$moralbonus)),$Mfactor);
			$holder = $holder / (1 + $holder);
			// Attacker
			$result[1] = $winner ? $holder : 1 - $holder;
			// Defender
			$result[2] = $winner ? 1 - $holder : $holder;
			$ram -= round($ram*$result[1]/100);
            $catp -= round($catp*$result[1]/100);  
		}
		else if($type == 3) {
			// Attacker
			$result[1] = ($winner)? pow((($rdp*$moralbonus)/$rap),$Mfactor) : 1;
			$result[1] = round($result[1],8);
            
			// Defender
			$result[2] = (!$winner)?  pow(($rap/($rdp*$moralbonus)),$Mfactor) : 1;
			$result[2] = round($result[2],8);
			// Als aangevallen met "Hero"
			$ku = ($att_tribe-1)*10+9;
            $kings = $Attacker['u'.$ku];   
			if (!is_numeric($kings)) { $kings = 0; }
            
            $aviables= $kings-round($kings*$result[1]);
			if ($aviables>0){
				switch($aviables){
				case 1:
				$fealthy = rand(20,30);
				break;
				case 2:
				$fealthy = rand(40,60);				
				break;
				case 3:
				$fealthy = rand(60,80);				
				break;
				case 4:
				$fealthy = rand(80,100);				
				break;
				default:
				$fealthy = 100;
				break;														
				}
				$result['health'] = $fealthy;
			}
			$ram -= ($winner)? round($ram*$result[1]/100) : round($ram*$result[2]/100);
            $catp -= ($winner)? round($catp*$result[1]/100) : round($catp*$result[2]/100);
        
		}
		// Formule voor de berekening van katapulten nodig
		 if($catp > 0 && $tblevel != 0) {
            $wctp = pow(($rap/$rdp),1.5);
            $wctp = ($wctp >= 1)? 1-0.5/$wctp : 0.5*$wctp;
            $wctp *= $catp;

            $need = round((($moralbonus * (pow($tblevel,2) + $tblevel + 1)) / (8 * (round(200 * pow(1.0205,$att_ab['a8']))/200) / (1 * $bid34[$stonemason]['attri']/100))) + 0.5);
            // Aantal katapulten om het gebouw neer te halen
            $result[3] = $need;
            // Aantal Katapulten die handeling
            $result[4] = $wctp;
            $result[5] = $moralbonus;
            $result[6] = $att_ab['a8'];
        }
        if($ram > 0 && $def_wall != 0) {
            $wctp = pow(($rap/$rdp),1.5);
            $wctp = ($wctp >= 1)? 1-0.5/$wctp : 0.5*$wctp;
            $wctp *= $ram;

            $need = round((($moralbonus * (pow($def_wall,2) + $def_wall + 1)) / (8 * (round(200 * pow(1.0205,$att_ab['a7']))/200) / (1 * $bid34[$stonemason]['attri']/100))) + 0.5);
            // Aantal katapulten om het gebouw neer te halen
            $result[7] = $need;
            
            // Aantal Katapulten die handeling
            $result[8] = $wctp;
        }

		$result[6] = pow($rap/$rdp*$moralbonus,$Mfactor);		

		$total_att_units = count($units['Att_unit']);
        $start = intval(($att_tribe-1)*10+1);
        $end = intval(($att_tribe*10));
        for($i=$start;$i <= $end;$i++){    
            $y = $i-(($att_tribe-1)*10);
            $result['casualties_attacker'][$y] = round($result[1]*$units['Att_unit'][$i]);              
        } 
        $result['casualties_attacker'][11] = 0;
        if ($units['Att_unit']['hero']>0){
            $hero_id=$atkhero['uid'];
            $hero_health=$atkhero['health'];
            $damage_health=round(100*$result[1]);
            if ($hero_health<=$damage_health or $damage_health>90){
                //hero die
                $result['casualties_attacker']['11'] = 1; 
				$database->modifyHero2('dead', 1, $hero_id, 0);
				$database->modifyHero2('health', 0, $hero_id, 0);
				$database->modifyHero2('experience', $result[1], $hero_id, 1);
            }else{
				$database->modifyHero2('health', $damage_health, $hero_id, 2);
				$database->modifyHero2('experience', $result[1], $hero_id, 1);
            }
        }
		
		if ($units['Def_unit']['hero']>0){
            $hero_id=$defenderhero['uid'];
            $hero_health=$defenderhero['health'];
            $damage_health=round(100*$result[2]);
            if ($hero_health<=$damage_health or $damage_health>90){
                //hero die
				$result['deadherodef'] = 1;
				$database->modifyHero2('dead', 1, $hero_id, 0);
				$database->modifyHero2('health', 0, $hero_id, 0);
				$database->modifyHero2('experience', $result[2], $hero_id, 1);
				mysql_query("UPDATE " . TB_PREFIX . "units SET `hero` = 0 WHERE `vref`='".$defenderhero['wref']."'");
            }else{
				$result['deadherodef'] = 0;
				$database->modifyHero2('health', $damage_health, $hero_id, 2);
				$database->modifyHero2('experience', $result[1], $hero_id, 1);
            }
        }

		$DefendersAll = $database->getEnforceVillage($DefenderWref,0);
		if(!empty($DefendersAll)){
			foreach($DefendersAll as $defenders) {
				if($defenders['hero']>0) {
					$fromvillage = $defenders['from'];
					$hero_id = $defhero[$fromvillage]['uid'];
					$hero_health = $defhero[$fromvillage]['health'];
					$damage_health = round(100*$result[2]);
					if ($hero_health<=$damage_health or $damage_health>90){
						//hero die
						$result['deadheroref'][$defenders['id']] = 1;
						$database->modifyHero2('dead', 1, $hero_id, 0);
						$database->modifyHero2('health', 0, $hero_id, 0);
						$database->modifyHero2('experience', $result[2], $hero_id, 1);
						mysql_query("UPDATE " . TB_PREFIX . "units SET `hero` = 0 WHERE `vref`='".$defhero[$fromvillage]['wref']."'");
					}else{
						$result['deadheroref'][$defenders['id']] = 0;
						$database->modifyHero2('health', $damage_health, $hero_id, 2);
						$database->modifyHero2('experience', $result[1], $hero_id, 1);
					}
				}
			}
		}
        //exit($result['casualties_attacker']['11']);
        

		// Work out bounty
        $start = ($att_tribe-1)*10+1;
        $end = ($att_tribe*10);
        $max_bounty = 0;

		for($i=$start;$i<=$end;$i++) {
						
			$varAttackerUnit = $Attacker['u'.$i];
			if(!is_numeric($varAttackerUnit)) { $varAttackerUnit = 0; }
			
            $y = $i-(($att_tribe-1)*10);  
			$max_bounty += ($varAttackerUnit-$result['casualties_attacker'][$y])*${'u'.$i}['cap'];
		}

		$result['bounty'] = $max_bounty;
		return $result;
	}
	
	public function resolveConflict($data) {
		global $database,$units,$unitsbytype;
		$UnitChief = $UnitRam = $UnitCatapult = 0;
		$attacker_count = $attack_infantry = $attack_cavalry = $attack_scout = $rams = $catapults = 0;
		$defender_count = $defense_infantry = $defense_cavalry = $defense_scout = $defense_heros = 0;
		$BonusPalRes = $BonusStoneMason = $BonusArtefactDurability = 0;
		$ExperienceAttacker = $ExperienceDefender = 0;
		$RecountReqd = $AllDefendersDead = false;

		$AttackArrivalTime = $data['endtime'];
		$AttackerData = $database->getVillageBattleData($data['from']);
		$AttackerData['pop'] = $database->getPopulation($AttackerData['id']);
		$Blacksmith = $database->getABTech($data['from']);

		for($i=1;$i<=11;$i++) {	$attacker_count += $data['t'.$i]; }
		if($data['type'] != 1) {
			// Trap attacking troops if this is not a scouting mission
		}

		for($i=1;$i<=10;$i++) {
			if($data['t'.$i] > 0) {
				$unit = ($AttackerData['tribe']-1)*10+$i;
				$unitdata = $GLOBALS['u'.$unit];
				if(in_array($unit,$unitsbytype['cavalry'])) {
					$attack_cavalry += $data['t'.$i] * ($unitdata['atk'] + ($unitdata['atk'] + 300 * $unitdata['pop'] / 7) * (pow(1.007,$Blacksmith['b'.$i]) - 1));
				} else {
					$attack_infantry += $data['t'.$i] * ($unitdata['atk'] + ($unitdata['atk'] + 300 * $unitdata['pop'] / 7) * (pow(1.007,$Blacksmith['b'.$i]) - 1));
				}
				if(in_array($unit,$unitsbytype['scout'])) {
					$attack_scout = $data['t'.$i] * 35 * pow(1.021,$Blacksmith['b'.$i]);
				}
				if(in_array($unit,$unitsbytype['chief'])) { $UnitChief = $i; }
				if(in_array($unit,$unitsbytype['ram'])) { $UnitRam = $i; }
				if(in_array($unit,$unitsbytype['catapult'])) { $UnitCatapult = $i; }
			}
		}
		if($data['t11'] == 1 && $data['type'] != 1) {
			$heroarrayAttacker = $this->getBattleHero($AttackerData['id']);
			$attack_cavalry += $heroarrayAttacker['power'];
			$attack_infantry *= $heroarrayAttacker['offBonus'];
			$attack_cavalry *= $heroarrayAttacker['offBonus'];
		}
		$attack_total = $attack_infantry + $attack_cavalry;
		if($attacker_count == 1 && $attack_total < 83 && $data['type'] != 1) {
			// kill the single non-scout low level attacker due to basic village defense
		}

		if ($database->isVillageOases($id) == 0) {
			$DefenderData = $database->getVillageBattleData($data['to']);
			$DefenderData['pop'] = $database->getPopulation($DefenderData['id']);
			$IsOasis = False;
		} else {
			$OasisData = $database->getOMInfo($data['to']);
			$IsOasis = True;
			if($OasisData['conqured'] == 0) {
				$DefenderData['pop'] = 500;
			} else {
				$DefenderData['pop'] = $database->getPopulation($OasisData['conqured']);
			}
			$DefenderData['tribe'] = 4;
			$DefenderData['wall'] = 0;
		}
		$DefenderUnits = $database->getUnit($data['to']);
		$DefendersAll = $database->getEnforceVillage($data['to'],0);
		array_unshift($DefendersAll,$DefenderUnits);
		foreach($DefendersAll as $defenders) {
			$definf = $defcav = 0;
			if(!empty($Armoury)) { reset($Armoury); }
			$Armoury = $defenders['from'] != $defenders['vref'] ? $database->getABTech($defenders['from']) : $database->getABTech($defenders['vref']);
			for($i=1;$i<=50;$i++) {
				if($defenders['u'.$i] > 0) {
					if(!empty($unitdata)) { reset($unitdata); }
					$unitdata = $GLOBALS['u'.$i];
					$definf += $defenders['u'.$i] * ($unitdata['di'] + ($unitdata['di'] + 300 * $unitdata['pop'] / 7) * (pow(1.007,$Armoury['a'.($i%10)]) - 1));
					$defcav += $defenders['u'.$i] * ($unitdata['dc'] + ($unitdata['dc'] + 300 * $unitdata['pop'] / 7) * (pow(1.007,$Armoury['a'.($i%10)]) - 1));
					if(in_array($i,$unitsbytype['scout'])) {
						$defense_scout += $defenders['u'.$i] * 20 * pow(1.03,$Armoury['a'.($i%10)]);
					}
					$defender_count += $defenders['u'.$i];
				}
			}
			if($defenders['hero'] == 1 && $data['type'] != 1) {
				if(!empty($heroarray)) { reset($heroarray); }
				if($defender['vref'] == $data['to']) {
					$heroarray = $this->getBattleHero($DefenderData['id']);
				} else {
					$ReinforcerData = $database->getVillageBattleData($defenders['from']);
					$heroarray = $this->getBattleHero($ReinforcerData['id']);
				}
				$definf = ($definf + $heroarray['defBonus']) * $heroarray['defBonus'];
				$defcav = ($defcav + $heroarray['power']) * $heroarray['power'];
				$defense_heros++;
			}
			$defense_infantry += $definf;
			$defense_cavalry += $defcav;
		}

		if($data['type'] == 1) {
			if($attack_scout > $defense_scout) {
				$attack_scout_casualties = pow(($defense_scout / $attack_scout),1.5);
				// generate scout report and process casualties
			} else {
				$attack_scout_casualties = 1;
				// kill all scouts
			}
		} else {
			$defense_total = $attack_infantry * $defense_infantry / $attack_total + $attack_cavalry * $defense_cavalry / $attack_total;

			if($DefenderData['pop'] < $AttackerData['pop']) {
				$defense_total *= min(1.5,pow($AttackerData['pop']/$DefenderData['pop'],0.2));
			}

			$DefenderFields = $database->getResourceLevel($data['to']);
			for($i=1;$i<=38;$i++) {
				if($DefenderFields['f'.$i] > 0) { $DefenderFieldsArray[] = $i; }
				if($DefenderFields['f'.$i.'t'] == 25 || $DefenderFields['f'.$i.'t'] == 26) {
					$BonusPalRes = 2 * pow($DefenderFields['f'.$i],2);
					$FieldPalRes = $i;
				}
				if($DefenderFields['f'.$i.'t'] == 34) {
					$BonusStoneMason = $DefenderFields['f'.$i] / 10 + 1;
				}
				if($DefenderFields['f'.$i.'t'] >= 5 && $DefenderFields['f'.$i.'t'] <= 9) {
					$ResourceImprovementArray[] = $i;
				}
				if($DefenderFields['f'.$i.'t'] == 36) {
					$TrapperArray[] = $i;
				}
				if($DefenderFields['f'.$i.'t'] == $data['ctar1'] && $data['ctar1'] != 0) {
					$ctarf[1] = $i;
				}
				if($DefenderFields['f'.$i.'t'] == $data['ctar2'] && $data['ctar2'] != 0 && ($data['ctar1'] != $data['ctar2'] || $data['ctar2'] <= 18)) {
					$ctarf[2]= $i;
				}
			}
			$defense_total += $BonusPalRes;

			$BonusWall = $DefenderData['tribe'] == 1 ? 1.03 : ($DefenderData['tribe'] == 2 ? 1.02 : 1.025);
			$defense_total *= pow($BonusWall,$DefenderData['wall']);

			if($attacker_count + $defender_count + $defense_heros > 1000) {
				$DiffModifier = 2 * (1.8592 - pow(($attacker_count + $defender_count + $defense_heros),0.015));
			} else {
				$DiffModifier = 1.5;
			}
			$attack_casualties = $defense_casualties = 1;
			if($attack_total > $defense_total) {
				$attack_casualties = pow(($defense_total / $attack_total),$DiffModifier);
				if($data['type'] == 4) {
					$attack_casualties = $attack_casualties / (1 + $attack_casualties);
					$defense_casualties = 1 - $attack_casualties;
				}
			} else {
				$defense_casualties = pow(($attack_total / $defense_total),$DiffModifier);
				if($data['type'] == 4) {
					$defense_casualties = $defense_casualties / (1 + $defense_casualties);
					$attack_casualties = 1 - $defense_casualties; 
				}
			}

			if($rams > 0 && $DefenderData['wall'] > 0) {
				if($attack_casualties < 1) {
					$database->setVillageLevel($data['to'],'f40t',0);
					$database->setVillageLevel($data['to'],'f40',0);
				} else {
					$RequiredRams=array(1=>array(1,2,2,3,4,6,7,10,12,14,17,20,23,27,31,35,39,43,48,53),array(1,4,8,13,19,27,36,46,57,69,83,98,114,132,151,171,192,214,238,263),array(1,2,4,6,8,11,15,19,23,28,34,40,46,53,61,69,77,86,96,106));
					$DC = max(1,$BonusStoneMason) * max(1,$BonusPalRes) / (pow(1.015,$Blacksmith['b'.$UnitRam])) ;
					$L = $DefenderData['wall'];
					$L2 = round(($DefenderData['wall'] - 1) /2);
					$DDR = $DC / ( $L*($L+1)/8 + 5 + (24.875 + 0.625*$L2)*$L2/2 );
					//calculate damage to wall based on surviving rams
				}
				$RecountReqd = True;
			}
			if($catapults > 0 && !$IsOasis) {
				$BuildLevelStrength=array(1=>1,2,2,3,4,6,8,10,12,14,17,20,23,27,31,35,39,43,48,53);
				$RequiredCatapults = $RequiredCatapultsMax = $BuildingLevelMax = array();
				if(!empty($RequiredCatapults)) { reset($RequiredCatapults); }
				for($i=1;$i<=2;$i++) {
					if($data['ctar'.$i] == 0 || $ctarf[$i] == 0) {
						$data['ctar'.$i] = $DefenderFieldsArray[rand(0,count($DefenderFieldsArray)-1)];
						if($data['ctar2'] == 0 && $i == 1) { $data['ctar2'] = $data['ctar1']; }
					}
					$RequiredCatapults[$i] = round((($DefenderData['pop'] < $AttackerData['pop'] ? min(3,pow($AttackerData['pop'] / $DefenderData['pop'],0.3)) : 1) * (pow($DefenderField['f'.$ctarf[$i]],2) + $DefenderField['f'.$ctarf[$i]] + 1) / (8 * (round(200 * pow(1.0205,$Blacksmith['b'.$UnitCatapult])) / 200) / max(1,($data['ctar'.$i]>=18?max(1,$BonusStoneMason + $BonusArtefactDurability):1)))) + 0.5);
					$BuildingLevelMax[$i] = 20;
					if($DefenderData['capital'] != 1 && $data['ctar'.$i] <= 18 || in_array($data['ctar'.$i],$TrapperArray)) { $BuildingLevelMax[$i] = 10; }
					if(in_array($data['ctar'.$i],$ResourceImprovementArray)) { $BuildingLevelMax[$i] = 5; }
					$RequiredCatapultsMax[$i] = round((($DefenderData['pop'] < $AttackerData['pop'] ? min(3,pow($AttackerData['pop'] / $DefenderData['pop'],0.3)) : 1) * (pow($BuildingLevelMax[$i],2) + $BuildingLevelMax[$i] + 1) / (8 * (round(200 * pow(1.0205,$Blacksmith['b'.$UnitCatapult])) / 200) / max(1,($data['ctar'.$i]>=18?max(1,$BonusStoneMason + $BonusArtefactDurability):1)))) + 0.5);
				}
				$CatapultsFiring = pow($attack_total / $defense_total,1.5);
				if($CatapultsFiring > 1) {
					$CatapultsFiring = 1 - 0.5 / $CatapultsFiring;
				} else {
					$CatapultsFiring = 0.5 * $CatapultsFiring;
				}
				$CatapultsFiring *= $data['t'.$UnitCatapult];
				for($i=1;$i=($data['ctar1']==$data['ctar2']?1:2);$i++) {
					$BuildingLevelOld[$i] = $DefenderField['f'.$data['ctar'.$i]];
					if($data['ctar1']!=$data['ctar2'] && $i==1) { $CatapultsFiring /= 2; }
					if($CatapultsFiring >= $RequiredCatapults[$i]) {
						if($DefenderField['f'.$data['ctar'.$i]] == $FieldPalRes) { $DestroyedPalRes = True; }
						if($data['ctar'.$i] >= 19) { $database->setVillageLevel($data['to'],'f'.$data['ctar'.$i].'t',0); }
						$database->setVillageLevel($data['to'],'f'.$data['ctar'.$i],0);
						$BuildingLevelNow[$i] = 0;
						$RecountReqd = True;
					} else {
						$BuildLevelCount = 0;
						for($j=$DefenderField['f'.$data['ctar'.$i]];$j=1;$j--) {
							$BuildLevelCount += ($BuildLevelStrength[$j] - $BuildLevelStrength[$j-1]) * $RequiredCatapultsMax[$i] / $BuildLevelStrength[$BuildingLevelMax[$i]];
							if($CatapultsFiring < $BuildLevelCount) {
								$BuildingLevelNow[$i] = $j;
								break;
							}
							$database->setVillageLevel($data['to'],'f'.$data['ctar'.$i],$BuildingLevelNow[$i]);
							$RecountReqd = True;
						}
					}
				}
			}

			for($i=1;$i<=10;$i++) {
				$attack_casualties_array[$i] = round($data['t'.$i] * $attack_casualties);
				if(!empty($unitdata)) { reset($unitdata); }
				$unitdata = $GLOBALS['u'.(($AttackerData['tribe']-1)*10+$i)];
				$ExperienceDefender += $attack_casualties_array[$i] * $unitdata['pop']; 
			}
			if($data['t11'] == 1) {
				if($attack_casualties < 0.9) {
					if($heroarrayAttacker['health']-100*$attack_casualties > 0) {
						$database->modifyHero('health',(100*$attack_casualties),$heroarrayAttacker['heroid'],2);
						$database->modifyHero('lastupdate',time(),$heroarrayAttacker['heroid'],0);
					} else {
						$database->modifyHero('health',0,$heroarrayAttacker['heroid'],0);
						$database->modifyHero('dead',1,$heroarrayAttacker['heroid'],0);
						$database->modifyHero('lastupdate',time(),$heroarrayAttacker['heroid'],0);
					}
				} else {
					$database->modifyHero('health',0,$heroarrayAttacker['heroid'],0);
					$database->modifyHero('dead',1,$heroarrayAttacker['heroid'],0);
					$database->modifyHero('lastupdate',time(),$heroarrayAttacker['heroid'],0);
				}
			}
			// send surviving attackers and hero home, report.
			// calculate defensive casualties, hero damage and experience (all heroes), modify units and reinforcements, report.
			// damage buildings report

			// Chiefing logic including wall and tribal specific building removal

			if($IsOasis && $data['t11'] == 1 && $AllDefendersDead) {
				if($database->canConquerOasis($data['from'],$data['to'])) {
					$database->conquerOasis($data['to'],$data['from'],$AttackerData['id']);
				}
			}

			if($RecountReqd) { $automation->recountPop($data['to']); }
		}
	}

	
};

$battle = new Battle;
?>