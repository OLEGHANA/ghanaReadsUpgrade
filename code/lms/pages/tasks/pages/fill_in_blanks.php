<?php session_start();include "../../../secure/talk2db.php";include "../../../functions/processGameTask.php";?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="../../../css/style.css">
<link href="../../../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../../js/jquery.js"></script><script src="../../../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../../css/jsDatePick_ltr.min.css" />
<link href="../../../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../../js/jsDatePick.min.1.3.js"></script>
<script type="text/javascript" src="../../../js/jquery.js"></script>
<script src="../../../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<title>Open Learning Exchange - Ghana</title>
</head>

<script type="text/javascript">
	window.onload = function(){
		///requestLoadLanguage();
		new JsDatePick({
			useMode:2,
			target:"endDate",
			dateFormat:"%Y-%m-%d"
		});
		new JsDatePick({
			useMode:2,
			target:"startDate",
			dateFormat:"%Y-%m-%d"
		});
	};
</script>
<body>
<form name="form1" method="post" action=""  onSubmit="return checkWords()">
  <div id="wrapper" style="background-color:#FFF; width:520px; height:600px">
    <div style="text-align:center; width:550px; margin-left:auto; margin-right:auto;">
    <span style="color:#00C; font-weight: bold;">Assign Task To Class</span> <br>
      - <a href="../../viewAllMyTaskResults.php?memberId=<?php echo $_GET['memberId'];?>&taskType=fill blanks"> View Previous Task Result </a>- <br />
      <table width="74%" border="0" align="center" cellpadding="2" cellspacing="2">
        <tr>
          <td><hr align="center" color="#0033FF"></td>
        </tr>
      </table>
      <br>
  <table width="468" border="0">
    <tr>
      <td width="129"><b>Starting Date</b></td>
      <td width="103"><span id="fldStartD">
        <input type="text" name="startDate" id="startDate" style="width:90px">
        <span class="textfieldRequiredMsg">required.</span><span class="textfieldInvalidFormatMsg">*.</span></span></td>
      <td width="66" align="right"><b>End Date</b></td>
      <td width="152"><span id="fldEndDate">
        <label for="endDate"></label>
        <input type="text" name="endDate" id="endDate"  style="width:90px">
        <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">*.</span></span></td>
</tr>
    <tr>
      <td><b> Level / Group </b>: </td>
      <td><span id="spryselect1">
        <select name="level" id="level" >
          <?php
		  	global $couchUrl;
			global $facilityId;
			$groups = new couchClient($couchUrl, "groups");
			//get all groups from view into viewResults
			$viewResults = $groups->include_docs(TRUE)->key($facilityId)->getView('api', 'allGroupsInFacility');
			$wCnt=0;
			while($wCnt<sizeof($viewResults->rows)){
				print '<option value="'.$viewResults->rows[$wCnt]->doc->_id.'">'.$viewResults->rows[$wCnt]->doc->name.'</option>';
				$wCnt++;
			}
			
			
          ?>
        </select>
        <span class="selectRequiredMsg">Please select an item.</span></span></td>
      <td align="right"><b>Language</b></td>
      <td><select name="Language" id="Language" onChange="requestLoadLanguage()" >
        <option value='aa'>Afar</option>
        <option value='ab'>Abkhazian</option>
        <option value='af'>Afrikaans</option>
        <option value='ak'>Akan</option>
        <option value='sq'>Albanian</option>
        <option value='am'>Amharic</option>
        <option value='ar'>Arabic</option>
        <option value='an'>Aragonese</option>
        <option value='hy'>Armenian</option>
        <option value='as'>Assamese</option>
        <option value='av'>Avaric</option>
        <option value='ae'>Avestan</option>
        <option value='ay'>Aymara</option>
        <option value='az'>Azerbaijani</option>
        <option value='ba'>Bashkir</option>
        <option value='bm'>Bambara</option>
        <option value='eu'>Basque</option>
        <option value='be'>Belarusian</option>
        <option value='bn'>Bengali</option>
        <option value='bh'>Bihari</option>
        <option value='bi'>Bislama</option>
        <option value='bo'>Tibetan</option>
        <option value='bs'>Bosnian</option>
        <option value='br'>Breton</option>
        <option value='bg'>Bulgarian</option>
        <option value='my'>Burmese</option>
        <option value='ca'>Catalan</option>
        <option value='ca'>Valencian</option>
        <option value='cs'>Czech</option>
        <option value='ch'>Chamorro</option>
        <option value='ce'>Chechen</option>
        <option value='zh'>Chinese</option>
        <option value='cu'>Church Slavic</option>
        <option value='cu'>Old Slavonic</option>
        <option value='cu'>Church Slavonic</option>
        <option value='cu'>Old Bulgarian;</option>
        <option value='cu'>Old Church Slavonic</option>
        <option value='cv'>Chuvash</option>
        <option value='kw'>Cornish</option>
        <option value='co'>Corsican</option>
        <option value='cr'>Cree</option>
        <option value='cy'>Welsh</option>
        <option value='da'>Danish</option>
        <option value='de'>German</option>
        <option value='dv'>Divehi</option>
        <option value='dv'>Dhivehi</option>
        <option value='dv'>Maldivian</option>
        <option value='nl'>Dutch; Flemish</option>
        <option value='dz'>Dzongkha</option>
        <option value='en' selected>English</option>
        <option value='eo'>Esperanto</option>
        <option value='et'>Estonian</option>
        <option value='ee'>Ewe</option>
        <option value='fo'>Faroese</option>
        <option value='fa'>Persian</option>
        <option value='fj'>Fijian</option>
        <option value='fi'>Finnish</option>
        <option value='fr'>French</option>
        <option value='fy'>Western Frisian</option>
        <option value='ff'>Fulah</option>
        <option value='ka'>Georgian</option>
        <option value='gd'>Gaelic</option>
        <option value='gd'>Scottish Gaelic</option>
        <option value='ga'>Irish</option>
        <option value='gl'>Galician</option>
        <option value='gv'>Manx</option>
        <option value='el'>Greek</option>
        <option value='gn'>Guarani</option>
        <option value='gu'>Gujarati</option>
        <option value='ht'>Haitian</option>
        <option value='ht'>Haitian Creole</option>
        <option value='ha'>Hausa</option>
        <option value='he'>Hebrew</option>
        <option value='hz'>Herero</option>
        <option value='hi'>Hindi</option>
        <option value='ho'>Hiri Motu</option>
        <option value='hr'>Croatian</option>
        <option value='hu'>Hungarian</option>
        <option value='ig'>Igbo</option>
        <option value='is'>Icelandic</option>
        <option value='io'>Ido</option>
        <option value='ii'>Sichuan Yi</option>
        <option value='iu'>Inuktitut</option>
        <option value='ie'>Interlingue</option>
        <option value='ia'>Interlingua</option>
        <option value='id'>Indonesian</option>
        <option value='ik'>Inupiaq</option>
        <option value='it'>Italian</option>
        <option value='jv'>Javanese</option>
        <option value='ja'>Japanese</option>
        <option value='kl'>Kalaallisut</option>
        <option value='kl'>Greenlandic</option>
        <option value='kn'>Kannada</option>
        <option value='ks'>Kashmiri</option>
        <option value='kr'>Kanuri</option>
        <option value='kk'>Kazakh</option>
        <option value='km'>Central Khmer</option>
        <option value='ki'>Kikuyu</option>
        <option value='ki'>Gikuyu</option>
        <option value='rw'>Kinyarwanda</option>
        <option value='ky'>Kirghiz</option>
        <option value='ky'>Kyrgyz</option>
        <option value='kv'>Komi</option>
        <option value='kg'>Kongo</option>
        <option value='ko'>Korean</option>
        <option value='kj'>Kuanyama</option>
        <option value='kj'>Kwanyama</option>
        <option value='ku'>Kurdish</option>
        <option value='lo'>Lao</option>
        <option value='la'>Latin</option>
        <option value='lv'>Latvian</option>
        <option value='li'>Limburgan</option>
        <option value='li'>Limburger</option>
        <option value='li'>Limburgish</option>
        <option value='ln'>Lingala</option>
        <option value='lt'>Lithuanian</option>
        <option value='lb'>Luxembourgish</option>
        <option value='lb'>Letzeburgesch</option>
        <option value='lu'>Luba-Katanga</option>
        <option value='lg'>Ganda</option>
        <option value='mk'>Macedonian</option>
        <option value='mh'>Marshallese</option>
        <option value='ml'>Malayalam</option>
        <option value='mi'>Maori</option>
        <option value='mr'>Marathi</option>
        <option value='ms'>Malay</option>
        <option value='mg'>Malagasy</option>
        <option value='mt'>Maltese</option>
        <option value='mo'>Moldavian</option>
        <option value='mn'>Mongolian</option>
        <option value='na'>Nauru</option>
        <option value='nv'>Navajo</option>
        <option value='nv'>Navaho</option>
        <option value='nr'>Ndebele South</option>
        <option value='nr'>South Ndebele</option>
        <option value='nr'>Ndebele North</option>
        <option value='nd'>North Ndebele</option>
        <option value='ng'>Ndonga</option>
        <option value='ne'>Nepali</option>
        <option value='nl'>Dutch</option>
        <option value='nn'>Norwegian Nynorsk</option>
        <option value='nn'>Nynorsk Norwegian</option>
        <option value='nb'>Bokmål Norwegian</option>
        <option value='nb'>Norwegian Bokmål</option>
        <option value='no'>Norwegian</option>
        <option value='ny'>Chichewa</option>
        <option value='ny'>Nyanja</option>
        <option value='oc'>Occitan </option>
        <option value='oc'>Provençal</option>
        <option value='oj'>Ojibwa</option>
        <option value='or'>Oriya</option>
        <option value='om'>Oromo</option>
        <option value='os'>Ossetian</option>
        <option value='os'>Ossetic</option>
        <option value='pa'>Panjabi</option>
        <option value='pa'>Punjabi</option>
        <option value='pi'>Pali</option>
        <option value='pl'>Polish</option>
        <option value='pt'>Portuguese</option>
        <option value='ps'>Pushto</option>
        <option value='qu'>Quechua</option>
        <option value='rm'>Romansh</option>
        <option value='ro'>Romanian</option>
        <option value='rn'>Rundi</option>
        <option value='ru'>Russian</option>
        <option value='sg'>Sango</option>
        <option value='sa'>Sanskrit</option>
        <option value='sr'>Serbian</option>
        <option value='si'>Sinhala</option>
        <option value='si'>Sinhalese</option>
        <option value='sk'>Slovak</option>
        <option value='sl'>Slovenian</option>
        <option value='se'>Northern Sami</option>
        <option value='sm'>Samoan</option>
        <option value='sn'>Shona</option>
        <option value='sd'>Sindhi</option>
        <option value='so'>Somali</option>
        <option value='st'>Sotho Southern</option>
        <option value='es'>Spanish</option>
        <option value='es'>Castilian</option>
        <option value='sc'>Sardinian</option>
        <option value='ss'>Swati</option>
        <option value='su'>Sundanese</option>
        <option value='sw'>Swahili</option>
        <option value='sv'>Swedish</option>
        <option value='ty'>Tahitian</option>
        <option value='ta'>Tamil</option>
        <option value='tt'>Tatar</option>
        <option value='te'>Telugu</option>
        <option value='tg'>Tajik</option>
        <option value='tl'>Tagalog</option>
        <option value='th'>Thai</option>
        <option value='ti'>Tigrinya</option>
        <option value='to'>Tonga </option>
        <option value='to'>Tonga Islands</option>
        <option value='tn'>Tswana</option>
        <option value='ts'>Tsonga</option>
        <option value='tk'>Turkmen</option>
        <option value='tr'>Turkish</option>
        <option value='tw'>Twi</option>
        <option value='ug'>Uighur</option>
        <option value='ug'>Uyghur</option>
        <option value='uk'>Ukrainian</option>
        <option value='ur'>Urdu</option>
        <option value='uz'>Uzbek</option>
        <option value='ve'>Venda</option>
        <option value='vi'>Vietnamese</option>
        <option value='vo'>Volapük</option>
        <option value='wa'>Walloon</option>
        <option value='wo'>Wolof</option>
        <option value='xh'>Xhosa</option>
        <option value='yi'>Yiddish</option>
        <option value='yo'>Yoruba</option>
        <option value='za'>Zhuang Chuang</option>
        <option value='zu'>Zulu</option>
        <option value='AsT'>Asante Twi</option>
          <option value='AkT'>Akuapem Twi</option>
          <option value='Ew'>Ewe</option>
          <option value='Ga'>Ga</option>
          <option value='Mfts'>Mfantse</option>
      </select></td>
    </tr>
  </table>
  <table width="468" border="0">
    <tr> </tr>
  </table>
    </div>
    <div style="float:none; width:600px; margin-left:auto; margin-right:auto;">
      <div id="audioStory" style="height:150px;">
        <table width="89%" align="left">
          <tr>
            <td colspan="4" align="center"><strong style="font-weight: bold; font-size: 18px; color: #00C;">Fill In The Blank Spaces</strong></td>
          </tr>
          <tr>
            <td colspan="4" align="center"><table border="0" align="left" cellpadding="2" cellspacing="2">
              <tr>
                <td align="center"><label for="word1"><b></b></label></td>
                <td align="center"><strong style="font-weight: bold; font-size: 18px; color: #00C;">Words</strong><br>
                  (Word must be more than three (3) characters and less than 11 Characters)</td>
                <td align="center">&nbsp;</td>
                <td align="center"><strong style="font-weight: bold; font-size: 18px; color: #00C;"> Time / Duration</strong></td>
                <td align="center"><strong style="font-weight: bold; font-size: 18px; color: #00C;">Blank Character</strong></td>
                </tr>
              <tr>
                <td width="5%"><span class="clear" style="font-size: 14px; color: #00C;">1.</span></td>
                <td><input type="text" name="word[]" id="word1" style="width:90%;" onKeyUp="checkFieldWord('word1','status1','duration1','blank_1','example_1','missing_1')"></td>
                <td width="6%"><img src="../../../images/icn_alert_error.png" id="status1" width="18" height="18" alt="word_status"></td>
                <td width="34%" align="center"><select name="duration[]" id="duration1"  style="display:none;">
                  <option value="30">30 Seconds</option>
                  <option value="50">50 Seconds</option>
                  <option value="60">60 Seconds</option>
                  <option value="80">1 min 20 Seconds</option>
                  <option value="120">2 minutes</option>
                  <option value="150">2 min 30 Seconds</option>
                  <option value="180">3 minutes</option>
                  <option value="210">3 min 30 Seconds</option>
                  <option value="240">4 minutes</option>
                  <option value="270">4 min 30 Seconds</option>
                  <option value="300">5 minutes</option>
                  <option value="330">5 min 30 Seconds</option>
                  <option value="360">6 minutes</option>
                  <option value="390">6 min 30 Seconds</option>
                  <option value="420">7 minutes</option>
                  <option value="450">7 min 30 Seconds</option>
                  <option value="480">8 minutes</option>
                  <option value="510">8 min 30 Seconds</option>
                  <option value="540">9 minutes</option>
                  <option value="570">9 min 30 Seconds</option>
                  <option value="600">10 minutes</option>
                  <option value="630">10 min 30 Seconds</option>
                </select></td>
                <td align="center">
                  <select name="blank[]" id="blank_1" style="display:none;" onChange="createBlanks('word1','blank_1','example_1','missing_1')">
                    <option value="1" selected >1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                  </select></td>
                </tr>
              <tr>
                <td width="5%">=&gt;</td>
                <td><span id="example_1" style="color: #90C; font-size: 14px;"></span></td>
                <td width="6%"><input type="hidden" name="missing[]" id="missing_1"></td>
                <td width="34%" align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td><span class="clear" style="font-size: 14px; color: #00C; text-align:center;">&nbsp;2.</span></td>
                <td><input type="text" name="word[]" id="word2"  style="width:90%;"   onKeyUp="checkFieldWord('word2','status2','duration2','blank_2','example_2','missing_2')"></td>
                <td><img src="../../../images/icn_alert_error.png" id="status2" width="18" height="18" alt="word_status"></td>
                <td align="center"><select name="duration[]" id="duration2"  style="display:none;">
                 <option value="30">30 Seconds</option>
                  <option value="50">50 Seconds</option>
                  <option value="60">60 Seconds</option>
                  <option value="80">1 min 20 Seconds</option>
                  <option value="120">2 minutes</option>
                  <option value="150">2 min 30 Seconds</option>
                  <option value="180">3 minutes</option>
                  <option value="210">3 min 30 Seconds</option>
                  <option value="240">4 minutes</option>
                  <option value="270">4 min 30 Seconds</option>
                  <option value="300">5 minutes</option>
                  <option value="330">5 min 30 Seconds</option>
                  <option value="360">6 minutes</option>
                  <option value="390">6 min 30 Seconds</option>
                  <option value="420">7 minutes</option>
                  <option value="450">7 min 30 Seconds</option>
                  <option value="480">8 minutes</option>
                  <option value="510">8 min 30 Seconds</option>
                  <option value="540">9 minutes</option>
                  <option value="570">9 min 30 Seconds</option>
                  <option value="600">10 minutes</option>
                  <option value="630">10 min 30 Seconds</option>
                </select></td>
                <td align="center">
                <select name="blank[]" id="blank_2"  style="display:none;" onChange="createBlanks('word2','blank_2','example_2','missing_2')">
                  <option value="1" selected>1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                </select></td>
                </tr>
              <tr>
                <td>=&gt;</td>
                <td><span id="example_2" style="color: #90C; font-size: 14px;"></span></td>
                <td><input type="hidden" name="missing[]" id="missing_2"></td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td><span class="clear" style="font-size: 14px; color: #00C; text-align:center;">&nbsp;3.</span></td>
                <td><input type="text" name="word[]" id="word3"  style="width:90%;"   onKeyUp="checkFieldWord('word3','status3','duration3','blank_3','example_3','missing_3')"></td>
                <td><img src="../../../images/icn_alert_error.png" id="status3" width="18" height="18" alt="word_status"></td>
                <td align="center"><select name="duration[]" id="duration3"  style="display:none;">
                  <option value="30">30 Seconds</option>
                  <option value="50">50 Seconds</option>
                  <option value="60">60 Seconds</option>
                  <option value="80">1 min 20 Seconds</option>
                  <option value="120">2 minutes</option>
                  <option value="150">2 min 30 Seconds</option>
                  <option value="180">3 minutes</option>
                  <option value="210">3 min 30 Seconds</option>
                  <option value="240">4 minutes</option>
                  <option value="270">4 min 30 Seconds</option>
                  <option value="300">5 minutes</option>
                  <option value="330">5 min 30 Seconds</option>
                  <option value="360">6 minutes</option>
                  <option value="390">6 min 30 Seconds</option>
                  <option value="420">7 minutes</option>
                  <option value="450">7 min 30 Seconds</option>
                  <option value="480">8 minutes</option>
                  <option value="510">8 min 30 Seconds</option>
                  <option value="540">9 minutes</option>
                  <option value="570">9 min 30 Seconds</option>
                  <option value="600">10 minutes</option>
                  <option value="630">10 min 30 Seconds</option>
                </select></td>
                <td align="center"><select name="blank[]" id="blank_3"  style="display:none;" onChange="createBlanks('word3','blank_3','example_3',missing_3)">
                  <option value="1" selected>1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                </select></td>
                </tr>
              <tr>
                <td>=&gt;</td>
                <td><span id="example_3" style="color: #90C; font-size: 14px;"></span></td>
                <td><input type="hidden" name="missing[]" id="missing_3"></td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td><span class="clear" style="font-size: 14px; color: #00C; text-align:center;">&nbsp;4.</span></td>
                <td><input type="text" name="word[]" id="word4"  style="width:90%;"   onKeyUp="checkFieldWord('word4','status4','duration4','blank_4','example_4','missing_4')"></td>
                <td><img src="../../../images/icn_alert_error.png" id="status4" width="18" height="18" alt="word_status"></td>
                <td align="center"><select name="duration[]" id="duration4" style="display:none;">
                 <option value="30">30 Seconds</option>
                  <option value="50">50 Seconds</option>
                  <option value="60">60 Seconds</option>
                  <option value="80">1 min 20 Seconds</option>
                  <option value="120">2 minutes</option>
                  <option value="150">2 min 30 Seconds</option>
                  <option value="180">3 minutes</option>
                  <option value="210">3 min 30 Seconds</option>
                  <option value="240">4 minutes</option>
                  <option value="270">4 min 30 Seconds</option>
                  <option value="300">5 minutes</option>
                  <option value="330">5 min 30 Seconds</option>
                  <option value="360">6 minutes</option>
                  <option value="390">6 min 30 Seconds</option>
                  <option value="420">7 minutes</option>
                  <option value="450">7 min 30 Seconds</option>
                  <option value="480">8 minutes</option>
                  <option value="510">8 min 30 Seconds</option>
                  <option value="540">9 minutes</option>
                  <option value="570">9 min 30 Seconds</option>
                  <option value="600">10 minutes</option>
                  <option value="630">10 min 30 Seconds</option>
                </select></td>
                <td align="center"><select name="blank[]" id="blank_4"  style="display:none;" onChange="createBlanks('word4','blank_4','example_4','missing_4')">
                  <option value="1" selected>1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                </select></td>
                </tr>
              <tr>
                <td>=&gt;</td>
                <td><span id="example_4" style="color: #90C; font-size: 14px;"></span></td>
                <td><input type="hidden" name="missing[]" id="missing_4"></td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><input type="hidden" name="grade" id="grade">
                  <input type="hidden" name="submitFillBlanks" id="submitStatus" value="false"></td>
                <td colspan="3"><input type="submit" class="button" value="Submit Task"></td>
              </tr>
            </table> 
            </td>
          </tr>
        </table>
      </div>
      <br>
      <br>
      <br>
      <br>
      <br>
      <br>
      <br>
<br>
      <br>
  </div>
  </div>
</form>
<script type="text/javascript">
	var now = new Date()
	var fmat= now.getFullYear()+'-'+ (now.getMonth()+1)+'-'+(now.getDay()+10)+' '+(now.getHours())+':'+(now.getMinutes())+':'+(now.getSeconds());
	///document.getElementById('systemDateForm').value = fmat;
</script>
<script type="text/javascript">

function checkWords(){
	wordFound = 1;
	var wordBox;
	var cnt=1;
	var lang = document.getElementById("Language").value;
	for(cnt=1;cnt<5;cnt++){
		wordBox = document.getElementById('word'+cnt).value;
		var NumberOFChar = wordBox.length;
			if(wordBox.length<3 || wordBox.length >10  ){
				//alert(cnt + 'field must be between 3 and 10 charactors' );
			}
			else{
				$.getJSON('../../../functions/checkWordExistByWordChar.php',{"lang":lang,"word":wordBox,"charsLength":NumberOFChar},
				function (data){
						if(data.query){
							document.getElementById('submitStatus').value = 'true'			
						}
				});
				
			}
	}
	
}

function checkFieldWord(feildID,feildStatus,durationTimer,blankSpace,example,missing){
	var wordBox;
	wordBox = document.getElementById(feildID).value;
	var cnt=1;
	if(wordBox.length < 11){
	setTimeout(function(){
		var lang = document.getElementById("Language").value;
		wordBox = document.getElementById(feildID).value;
		var firstChar = wordBox[0];
		wordBox = firstChar.toUpperCase() + (wordBox.substring(1,wordBox.length)).toLowerCase();
		///alert(wordBox);
		var NumberOFChar = wordBox.length;
		document.getElementById(feildID).value = wordBox;
		//if(wordBox.length<3){
		//	document.getElementById(feildStatus).src='../../../images/icn_alert_error.png';
		//}else{
		$.getJSON('../../../functions/checkWordExistByWordChar.php',{"lang":lang,"word":wordBox,"charsLength":NumberOFChar},
				function (data){
						if(data.query){
							document.getElementById(feildStatus).src='../../../images/icn_alert_success.png';
							document.getElementById(durationTimer).style.display ='block';
							document.getElementById(blankSpace).style.display ='block';
							createBlanks(feildID,blankSpace,example,missing);
						}else{
							document.getElementById(feildStatus).src='../../../images/icn_alert_error.png';
							document.getElementById(durationTimer).style.display ='none';
							document.getElementById(blankSpace).style.display ='none';		
							document.getElementById(example).innerHTML = "";
							document.getElementById(missing).value = "";
						}
				});
			//}
			},600);
	}else{
		alert('Word field must less than 10 charactors' );
	}

	
}
function createBlanks(feildID,blankSpace,example,missing){
	oppWord = document.getElementById(feildID).value;
	var arrayVar = [];
	///var arrayOppWord = JSON.parse("["+oppWord+"]")
	var noOFWordChar = oppWord.length;
	if(noOFWordChar > 3){
		var blankSpaces = document.getElementById(blankSpace).value
		var arayBlankHolder = [];
		for(var conv_cnt=0;conv_cnt < oppWord.length; conv_cnt++){
			arrayVar.push(oppWord[conv_cnt]);
		}
		if((noOFWordChar -2) >= blankSpaces){
			for(var cnt=0;cnt<blankSpaces;cnt++){
				var randIndex = Math.floor((Math.random()*noOFWordChar) + 1);
				var indexSearch = arayBlankHolder.indexOf(randIndex);
				if (indexSearch > -1) {
					cnt--;
				}else{
					arayBlankHolder.push(randIndex);
				}
				
			}
			for(var ex_cnt=0;ex_cnt<arayBlankHolder.length;ex_cnt++){
				var charAtValue = parseInt(arayBlankHolder[ex_cnt]);
				arrayVar[charAtValue] = '_';
			}
			document.getElementById(example).innerHTML =arrayVar.join(' ');
			document.getElementById(missing).value = arayBlankHolder.sort();
			
			//alert('Will work ' + arrayVar.join(' '));
		}else{
			alert('Selected blank spaces shouldnt exceed number of characters in word. Blanks must be less than ' +(noOFWordChar-1));
		}
  }
}
var sprytextfield2 = new Spry.Widget.ValidationTextField("fldEndDate", "none");
var sprytextfield1 = new Spry.Widget.ValidationTextField("fldStartD", "none");
</script>
</body>
</html>
