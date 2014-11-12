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
     */
    private function initialiseExport($form, $file_name = false)
    {
        $this->form = $form;
        $this->file_name = $file_name;
        $this->csv = new CsvWriter(new \SplTempFileObject);
    }

    /**
     * @param $form
     * @param bool $file_name
     */
    public function exportToFile($form, $file_name = false)
    {
        $this->initialiseExport($form, $file_name);
        $this->buildCsv();

        $this->filesystem->put($this->generateFileName(), $this->csv->__toString());
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
        header('Content-Disposition: attachment; filename="' . $this->file_name . '"');

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
    private function buildCsv()
    {
        $this->addHeadings();
        $this->addSubmissions();
    }

    /**
     *
     */
    private function addHeadings()
    {
        $this->headings = $this->formsRepository->dataHeadings($this->form);

        $this->csv->insertOne($this->headings);
    }

    /**
     *
     */
    private function addSubmissions()
    {
        $limit = 100;
        $offset = 0;

        while(true)
        {
            $submissions = $this->formsRepository->getSubmissions($this->form->id, $offset, $limit);

            if ($submissions->count() == 0)
            {
                break;
            }

            foreach ($submissions as $submission)
            {
                $row = [];

                foreach ($submission->fields as $field)
                {
                    $row[$field->identifier] = $field->display_export;
                }

                $this->addSubmissionRow($row);
            }

            $offset += $limit;
        }
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