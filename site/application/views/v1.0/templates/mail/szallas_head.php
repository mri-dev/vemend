<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html4"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="http://www.facebook.com/2008/fbml" lang="hu-HU">
<head>
	<title></title>
    <style type="text/css">
        * {
        }
        body, html {
            font-size: 13px;
            margin:0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }
        body {
            background: #f3f3f3;
        }

        header{
          padding: 10px 0;
        }

        header, footer {
          background: #ffffff;
          color: white;
        }

        footer{
          background: #333333;
        }

        a:link, a:visited {
            color:#f5b801;
        }
        .width {
            width: 800px;
             margin: 0 auto;
        }
        .pad {
            padding: 10px 0;
        }
        .bar {
            background: #f5b801;
        }
        .bar td {
            text-align: center;
        }
        .bar td a {
           font-size: 14px !important;
        }

        .bar table {
            margin: 0 auto;
        }

        .bar a {
            color: white;
            font-weight: bold;
            text-decoration: none;
            line-height: 1;
            padding: 10px 0;
            display: block;
        }
        .cdiv {
            height: 10px;
            background: #f5b801;
            display: block;
            position: relative;
        }
        .wb {
            background: #d9d9d9;
            font-size: 10px;
            color: #333333;
        }
        .radius {
            -webkit-border-radius: 8px;
            -moz-border-radius: 8px;
            border-radius: 8px;
        }
        .content-holder {
            background: #fff;
            color: #404040;
            padding: 25px;
        }

        .content-holder h1{
          color: black;
          margin: 0 0 25px 0;
        }

        .footer {
            text-align: center;
            font-size: 12px !important;
            color: #ffffff !important;
        }
        .footer a {
          color: white;
        }
        .footer .row {
            margin: 5px 0;
        }
        .footer tr td {
            text-align: center;
            border-right: 1px solid #ffffff;
            font-size: 12px !important;
            color: #ffffff !important;
        }
         .footer tr td:last-child {
            border-right: none;
        }

        .footer .info {
            font-size: 11px;
            color: #8c8c8c;
        }
        .relax {
            color: #000000;;
            font-size: 18px;
        }
         table.if {
            font-size: 12px;
            color: #4c4c4c;
        }

         table.if strong {
            color: #222222;
        }
        table.if td{
          padding: 10px;
        }
        table.if th{
          background: #fafafa;
          text-align: left;
          padding: 10px;
        }
        table.if,
        table.if td,
        table.if th {
            border: 1px solid #d7d7d7;
            border-collapse: collapse;
        }

        table.if tbody td a {
            font-weight: bold;
        }

        header table td {
          vertical-align: top;
        }

        .szallas_top_header h2 {
          text-transform: uppercase;
          font-size: 17px;
          margin: 0 0 10px 0;
        }
        .szallas_top_header h4 {
          font-size: 15px;
          color: #f77427;
          margin: 4px 0;
        }
        .szallas_top_header .address {
          font-size: 12px;
          color: #888888;
        }
        .szallas_top_header .refid{
          font-size: 10px;
          margin: 4px 0 0 0;
        }

        @media all and (max-width: 800px){
          .width{
            width: 100%;
          }
        }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
</head>
<body>

<header>
  <div class="width">
      <table width="100%" border="0" style="border:none;">
          <tr>
              <tbody>
                  <tr>
                      <td  width="125" style="text-align:left;">
                          <img src="<?=IMGDOMAIN?>src/images/vemend_logo.jpg" alt="<?=$settings['page_title']?>" style="width:auto !important; height:40px;">
                      </td>
                      <td style="text-align:right; font-size:14px; color:#000;" >
                          <div class="szallas_top_header">
                            <div class="">
                              <h2>Szállás ajánlatkérő értesítő</h2>
                            </div>
                            <div class="">Szállás:</div>
                            <div class="">
                              <h4><?=$szallas['title']?></h4>
                            </div>
                            <div class="address">
                              <?=$szallas['cim']?>
                            </div>
                            <div class="refid" title="Ajánlat referencia azonosítója">
                            RFID: #<?=$rfid?>
                            </div>
                          </div>
                      </td>
                  </tr>
              </tbody>
          </tr>
      </table>
  </div>
</header>

<div class="bar">
    <div class="width">
        <table border="0" style="border:none; width: 100%;">
            <tr>
                <tbody>
                    <tr>
                        <td style="width: 25%;"><a href="<?=$settings['page_url']?><?=$szallas['url']?>">Szállás adatlapja</a></td>
                        <td style="width: 25%;"><a href="<?=$settings['page_url']?>//cikkek/onkormanyzati-hirek">Önkormányzati hírek</a></td>
                        <td style="width: 25%;"><a href="<?=$settings['page_url']?>/p/adatvedelmi-tajekoztato">Adatvédelmi tájékoztató</a></td>
                        <td style="width: 25%;"><a href="<?=$settings['blog_url']?>/kapcsolat">Kapcsolat</a></td>
                    </tr>
                </tbody>
            </tr>
        </table>
    </div>
</div>

<div class="width">
<div class="in-content">
    <div class="content-holder">
