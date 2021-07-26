<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function btn_edit($uri) {
    return anchor($uri, 'Edit', array('title' => "Edit Content"));
}

function btn_delete($uri) {
    return anchor($uri, 'Delete', array('onclick' => 'return confirm(\'Are you sure to delete this?\')', 'title' => "Delete Content"));
}

function dateformat($date, $format = 'date') {
    $time = strtotime($date);
    if ($format == 'datetime') {
        return date('M d Y H:i', $time);
    } else if ($format == 'time') {
        return date('h:i A', $time);
    } else if ($format == 'date') {
        return date('M d Y', $time);
    }
}

function dateformat_db($date, $format = 'date') {
    $time = strtotime($date);
    if ($format == 'datetime') {
        return date('Y-m-d H:i:s', $time);
    } else if ($format == 'time') {
        return date('H:i:s', $time);
    } else if ($format == 'date') {
        return date('Y-m-d', $time);
    }
}

function pr($data,$put_die = 0){
    echo "<pre>";
        print_r($data);
    echo "</pre>"; 
    if($put_die){
        die();
    }
}

function cellColor($cells,$color,$sheet,$font_color ='877a44'){
    //echo $cells;
    //$objPHPExcel;
    //echo $font_color; exit;
   $sheet->getStyle($cells)->getFill()->applyFromArray(array(
       'type' => PHPExcel_Style_Fill::FILL_SOLID,
       'startcolor' => array(
            'rgb' => $color
       ),
       'font'  => array(
        'color' => array('rgb' => $font_color)
      )
   ));
   return $sheet;
 }
 
 function setcellborder($cells,$color,$sheet){
 
   $sheet->getStyle($cells)->applyFromArray(
       array(
           'borders' => array(
               'allborders' => array(
                   'style' => PHPExcel_Style_Border::BORDER_THIN,
                   'color' => array('rgb' => $color)
               )
           )
       )
  );
  return $sheet;
 }
 function meargecells($sheet,$cells,$start_cell,$cell_value,$is_center){
           $sheet->mergeCells($cells);
           $sheet
           ->getCell($start_cell)
           ->setValue($cell_value);
           if($is_center){
               $sheet
               //->getActiveSheet()
               ->getStyle($start_cell)
               ->getAlignment()
               ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);    
           } 
          return $sheet;           
 
 }





