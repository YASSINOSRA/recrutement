<?php

namespace App\Http\Livewire;

use App\Models\JobApplication;
use App\Models\User;
use App\Models\Candidate;
use App\Models\CandidateLanguage;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class JobApplicationTable extends LivewireTableComponent
{
    protected $model = JobApplication::class;

    public $showButtonOnHeader = true;
    public $buttonComponent = 'employer.job_applications.table_components.edit_button';
    public $jobId;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableAttributes([
            'default' => false,
            'class' => 'table table-striped',
        ]);
        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            return in_array($column->getField(), ['0', '1', '2', '3', '4', '5']) ? ['class' => 'text-center'] : [];
        });
        $this->setQueryStringStatus(false);
        $this->setFilterPillsStatus(false);
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.job_application.candidate_name'), 'candidate.user.first_name')
                ->sortable()
                ->searchable()
                ->view('employer.job_applications.table_components.candidate_name'),

            Column::make(__('messages.candidate.expected_salary'), 'candidate.expected_salary')
                ->sortable()
                ->searchable()
                ->view('employer.job_applications.table_components.expected_salary'),

            Column::make(__('messages.job_application.application_date'), 'created_at')
                ->sortable()
                ->searchable()
                ->view('employer.job_applications.table_components.application_date'),

            Column::make(__('messages.apply_job.resume'), 'candidate.user.last_name')
                ->view('employer.job_applications.table_components.resume'),

            Column::make(__('messages.job_stage.job_stage'), 'job_stage_id')
                ->view('employer.job_applications.table_components.job_stage'),

            Column::make(__('messages.common.status'), 'status')
                ->searchable()
                ->view('employer.job_applications.table_components.status'),

            Column::make(__('messages.common.action'), 'id')
                ->view('employer.job_applications.table_components.action_button'),
        ];
    }

    public function builder(): Builder
    {
        return JobApplication::with(['job.currency', 'candidate.user', 'jobStage', 'job'])
            ->where('job_id', $this->jobId)
            ->where('status', '!=', JobApplication::STATUS_DRAFT)
            ->select('job_applications.*');
    }

    public function filters(): array 
    {
        return [
            SelectFilter::make(__('messages.common.status'))
                ->options([
                    '' => __('messages.filter_name.select_status'),
                    1 => __('messages.common.applied'),
                    2 => __('messages.common.declined'),
                    3 => __('messages.common.hired'),
                    4 => __('messages.common.ongoing'),
                ])
                ->filter(fn(Builder $builder, string $value) => $builder->where('status', '=', $value)),

            SelectFilter::make(__('messages.filterjob.experience'))
                ->options([
                    '' => __('messages.filterjob.select_experience'),
                    'lt_1' => __('messages.filterjob.less_than_1'),
                    'btwn_1_3' => __('messages.filterjob.between_1_and_3'),
                    'btwn_3_5' => __('messages.filterjob.between_3_and_5'),
                    'gt_5' => __('messages.filterjob.greater_than_5'),
                ])
                ->filter(function (Builder $builder, string $value) {
                    $builder->whereHas('candidate', function (Builder $query) use ($value) {
                        if ($value === 'lt_1') {
                            $query->where('experience', '<', 1);
                        } elseif ($value === 'btwn_1_3') {
                            $query->whereBetween('experience', [1, 3]);
                        } elseif ($value === 'btwn_3_5') {
                            $query->whereBetween('experience', [3, 5]);
                        } elseif ($value === 'gt_5') {
                            $query->where('experience', '>', 5);
                        }
                    });
                }),

            SelectFilter::make(__('messages.filterjob.language'))
                ->options([
                    '' => __('messages.filterjob.select_language'),
                    1 => 'English',
                    2 => 'French',
                    10 => 'Arabic',
                    11 => 'Amazigh',
                ])
                ->filter(function (Builder $builder, string $value) {
                    $builder->whereHas('candidate.user.candidateLanguages', function (Builder $query) use ($value) {
                        $query->where('language_id', $value);
                    });
                }),
        ];
    }
}
