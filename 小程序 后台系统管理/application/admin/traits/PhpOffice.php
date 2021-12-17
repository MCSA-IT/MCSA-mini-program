<?php
/**
 * phpOffice相关操作
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\traits;

use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

trait PhpOffice
{

    public  function exportData($head, $body, $name = '', $version = '2007',$title='导出记录')
    {
        try {
            // 输出 Excel 文件头
            if(empty($name)){
                $name =date('Y-m-d-H-i-s');
            }

            $spreadsheet   = new Spreadsheet();
            $sheetPHPExcel = $spreadsheet->setActiveSheetIndex(0);
            $char_index    = range('A', 'Z');
            //处理超过26列
            $a = 'A';
            foreach ($char_index as $item){
                $char_index[] = $a . $item;
            }

            // Excel 表格头
            foreach ($head as $key => $val) {
                $sheetPHPExcel->setCellValue("{$char_index[$key]}1", $val);
            }

            $spreadsheet->getActiveSheet()->setTitle($title);

            // Excel body 部分
            foreach ($body as $key => $val) {
                $row = $key + 2;
                $col = 0;
                foreach ($val as $k => $v) {
                    $spreadsheet->getActiveSheet()->setCellValue("{$char_index[$col]}{$row}", $v);
                    $col++;
                }
            }

            // 版本差异信息
            $version_opt = [
                '2007' => [
                    'mime'       => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'ext'        => '.xlsx',
                    'write_type' => 'Xlsx',
                ],
                '2003' => ['mime'       => 'application/vnd.ms-excel',
                           'ext'        => '.xls',
                           'write_type' => 'Xls',
                ],
                'pdf'  => ['mime'       => 'application/pdf',
                           'ext'        => '.pdf',
                           'write_type' => 'PDF',
                ],
                'ods'  => ['mime'       => 'application/vnd.oasis.opendocument.spreadsheet',
                           'ext'        => '.ods',
                           'write_type' => 'OpenDocument',
                ],
            ];

            header('Content-Type: ' . $version_opt[$version]['mime']);
            header('Content-Disposition: attachment;filename="' . $name . $version_opt[$version]['ext'] . '"');
            header('Cache-Control: max-age=0');

            $objWriter = IOFactory::createWriter($spreadsheet, 'Xlsx');
            return $objWriter->save('php://output');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    //导出带图片的内容
    public  function exportData_new($head, $body, $name = '', $version = '2007',$title='导出记录')
    {
        try {
            // 输出 Excel 文件头
            if(empty($name)){
                $name =date('Y-m-d-H-i-s');
            }

            $spreadsheet   = new Spreadsheet();
            $sheetPHPExcel = $spreadsheet->setActiveSheetIndex(0);
            $char_index    = range('A', 'Z');
            //处理超过26列
            $a = 'A';
            foreach ($char_index as $item){
                $char_index[] = $a . $item;
            }

            // Excel 表格头
            foreach ($head as $key => $val) {
                $sheetPHPExcel->setCellValue("{$char_index[$key]}1", $val);
            }

            $spreadsheet->getActiveSheet()->setTitle($title);
            // Excel body 部分
            $i = 2;
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(32);
            foreach ($body as $key => $val) {
                $spreadsheet->getActiveSheet()->getRowDimension($i)->setRowHeight(160);
                $row = $key + 2;
                $col = 0;
                foreach ($val as $k => $v) {
                    if($col==2&&!empty($v)){
                        $objDrawing = new Drawing();
                        $objDrawing->setPath('.' . $v);//这里拼接 . 是因为要在根目录下获取
                        // 设置宽度高度
                        $objDrawing->setHeight(200);//照片高度
                        $objDrawing->setWidth(200); //照片宽度
                        /*设置图片要插入的单元格*/
                        $objDrawing->setCoordinates('C' . $i);
                        // 图片偏移距离
                        $objDrawing->setOffsetX(0);
                        $objDrawing->setOffsetY(0);
                        $objDrawing->setWorksheet($spreadsheet->getActiveSheet());
                        $i++;
                    }else{
                        $spreadsheet->getActiveSheet()->setCellValue("{$char_index[$col]}{$row}", $v);
                    }
                    $col++;
                }
            }

            // 版本差异信息
            $version_opt = [
                '2007' => [
                    'mime'       => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'ext'        => '.xlsx',
                    'write_type' => 'Xlsx',
                ],
                '2003' => ['mime'       => 'application/vnd.ms-excel',
                    'ext'        => '.xls',
                    'write_type' => 'Xls',
                ],
                'pdf'  => ['mime'       => 'application/pdf',
                    'ext'        => '.pdf',
                    'write_type' => 'PDF',
                ],
                'ods'  => ['mime'       => 'application/vnd.oasis.opendocument.spreadsheet',
                    'ext'        => '.ods',
                    'write_type' => 'OpenDocument',
                ],
            ];

            header('Content-Type: ' . $version_opt[$version]['mime']);
            header('Content-Disposition: attachment;filename="' . $name . $version_opt[$version]['ext'] . '"');
            header('Cache-Control: max-age=0');

            $objWriter = IOFactory::createWriter($spreadsheet, 'Xlsx');
            return $objWriter->save('php://output');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}