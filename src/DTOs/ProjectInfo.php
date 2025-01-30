<?php
namespace Rstacode\Otpiq\DTOs;

class ProjectInfo
{
    public function __construct(
        public readonly string $projectName,
        public readonly int $credit
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            projectName: $data['projectName'],
            credit: $data['credit']
        );
    }

    public function toArray(): array
    {
        return [
            'projectName' => $this->projectName,
            'credit'      => $this->credit,
        ];
    }
}
