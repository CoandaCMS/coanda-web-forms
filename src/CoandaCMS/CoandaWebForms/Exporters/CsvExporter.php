<?php namespace CoandaCMS\CoandaWebForms\Exporters;

use League\Csv\Writer as CsvWriter;

class CsvExporter {

    /**
     * @var
     */
    private $csv;
    /**
     * @var
     */
    private $form;
    /**
     * @var
     */
    private $file_name;

    /**
     * @var
     */
    private $date_range_from;
    /**
     * @var
     */
    private $date_range_to;

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    private $filesystem;
    /**
     * @var
     */
    private $headings;
    /**
     * @var \CoandaCMS\CoandaWebForms\Repositories\WebFormsRepositoryInterface
     */
    private $formsRepository;

    /**
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     * @param \CoandaCMS\CoandaWebForms\Repositories\WebFormsRepositoryInterface $formsRepository
     */
    public function __construct(\Illuminate\Filesystem\Filesystem $filesystem, \CoandaCMS\CoandaWebForms\Repositories\WebFormsRepositoryInterface $formsRepository)
    {
        $this->filesystem = $filesystem;
        $this->formsRepository = $formsRepository;
    }

    /**
     * @param $form
     * @param bool $file_name
     * @param array $date_range
     */
    private function initialiseExport($form, $file_name = false, $date_range = [])
    {
        $this->form = $form;
        $this->file_name = $file_name;
        $this->csv = new CsvWriter(new \SplTempFileObject);

        $this->handleDateRange($date_range);
    }

    /**
     * @param $date_range
     */
    private function handleDateRange($date_range)
    {
        $this->date_range_from = isset($date_range['from']) ? $date_range['from'] : false;
        $this->date_range_to = isset($date_range['to']) ? $date_range['to'] : false;
    }

    /**
     * @param $form
     * @param bool $file_name
     * @param array $date_range
     * @return string
     */
    public function exportToFile($form, $file_name = false, $date_range = [], $update_download_id = false)
    {
        $this->initialiseExport($form, $file_name, $date_range);
        $this->buildCsv($update_download_id);

        $this->filesystem->put($this->generateFileName(), $this->csv->__toString());

        return $this->generateFileName();
    }

    /**
     * @param $form
     * @param bool $file_name
     */
    public function exportToBrowser($form, $file_name = false)
    {
        $this->initialiseExport($form, $file_name);
        $this->buildCsv();

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $this->file_name . '.csv"');

        $this->csv->output();
        exit;
    }

    /**
     * @return string
     */
    private function generateFileName()
    {
        if ($this->file_name)
        {
            return $this->file_name;
        }

        $cache_path = storage_path() . '/webformsubmissions';

        if (!is_dir($cache_path))
        {
            mkdir($cache_path);
        }

        return $cache_path . '/Form-' . $this->form->id . '-' . date('d-m-Y-H-i-s', time()) . '.csv';
    }

    /**
     *
     */
    private function buildCsv($update_download_id = false)
    {
        $this->addHeadings();
        $this->addSubmissions($update_download_id);
    }

    /**
     *
     */
    private function addHeadings()
    {
        $this->headings = $this->formsRepository->dataHeadings($this->form);

        $this->headings['created_at'] = 'Submitted';

        $this->csv->insertOne($this->headings);
    }

    /**
     *
     */
    private function addSubmissions($update_download_id = false)
    {
        if ($update_download_id) {
            $total = $this->formsRepository->getSubmissions($this->form->id, false, false, $this->date_range_from, $this->date_range_to, true);

            $this->formsRepository->updateDownloadPercentage($update_download_id, 0);
        }

        $offset = 0;
        $limit = 100;

        while(true)
        {
            if ($this->loopSubmissions($offset, $limit)) {
                break;
            }

            if (isset($total)) {
                $percentage = ( ($offset + $limit) / $total) * 100;
                $this->formsRepository->updateDownloadPercentage($update_download_id,  $percentage );
            }

            $offset += $limit;
        }
    }

    private function loopSubmissions($offset, $limit)
    {
        $submissions = $this->formsRepository->getSubmissions($this->form->id, $offset, $limit, $this->date_range_from, $this->date_range_to, false);

        if ($submissions->count() == 0)
        {
            return true;
        }

        foreach ($submissions as $submission)
        {
            $row = [];

            foreach ($submission->fields as $field)
            {
                $row[$field->identifier.'-'.$field->field_id] = $field->display_export;
            }

            $row['created_at'] = $submission->created_at;

            $this->addSubmissionRow($row);
        }

        return false;
    }

    /**
     * @param $submission_row
     */
    private function addSubmissionRow($submission_row)
    {
        $row = [];

        // Make sure the fields are in the same order as the headings
        foreach (array_keys($this->headings) as $heading_identifier)
        {
            $row[] = isset($submission_row[$heading_identifier]) ? $submission_row[$heading_identifier] : '';
        }

        $this->csv->insertOne($row);
    }
}