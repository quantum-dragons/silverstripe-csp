<?php
/**
 * CSP Violation Report
 * @note refer to https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP#Sample_violation_report
 * @author james.ellis@dpc.nsw.gov.au
 */
class CspViolationReport extends DataObject {

  /**
   * Singular name for CMS
   * @var string
   */
  private static $singular_name = 'Report';
  private static $plural_name = 'Reports';

  /**
   * Database fields
   * @var array
   */
  private static $db = [
    'DocumentUri' => 'Varchar(255)',
    'Referrer' => 'Varchar(255)',
    'BlockedUri' => 'Varchar(255)',
    'ViolatedDirective' => 'Varchar(255)',
    'OriginalPolicy' => 'Text',
    'SourceFile' => 'Varchar(255)',
    'LineNumber' =>'Int',
    'ColumnNumber' =>'Int',
    'Disposition' => 'Varchar(32)',
    'UserAgent' => 'Varchar(255)',
  ];

  private static $indexes = [
    'DocumentUri' => true,
    'LastEdited' => true,
    'Created' => true,
  ];

  /**
   * Defines summary fields commonly used in table columns
   * as a quick overview of the data for this dataobject
   * @var array
   */
  private static $summary_fields = [
    'ID' => '#',//for referring to report numbers
    'Created.Nice' => 'Created',
    'Disposition' => 'Disposition',
    'DocumentUri' => 'Document URI',
    'BlockedUri' => 'Blocked URI',
    'ViolatedDirective' => 'Directive',
  ];

  private static $default_sort = 'Created DESC';

  /**
   * Create a new Violation Report per data spec
   */
  public static function create_report($data, $user_agent) {
    $report = new CspViolationReport();
    $report->DocumentUri = isset($data['document-uri']) ? $data['document-uri'] : '';
    $report->Referrer = isset($data['referrer']) ? $data['referrer'] : '';
    $report->BlockedUri = isset($data['blocked-uri']) ? $data['blocked-uri'] : '';
    $report->ViolatedDirective = isset($data['violated-directive']) ? $data['violated-directive'] : '';
    $report->OriginalPolicy = isset($data['original-policy']) ? $data['original-policy'] : '';
    $report->LineNumber =  isset($data['line-number']) ? $data['line-number'] : '';
    $report->ColumnNumber =  isset($data['column-number']) ? $data['column-number'] : '';
    $report->Disposition =  isset($data['disposition']) ? $data['disposition'] : '';
    $report->SourceFile =  isset($data['source-file']) ? $data['source-file'] : '';
    $report->UserAgent = $user_agent;
    $report->write();
    return $report;
  }

  /**
   * In a report, all fields are readonly
   * @return FieldList
   */
  public function getCMSFields()
  {
    $fields = parent::getCMSFields();
    $fields = $fields->transform( new ReadonlyTransformation() );
    return $fields;
  }

}
