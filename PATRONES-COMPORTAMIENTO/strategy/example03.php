<?php

class FormatContext
{
  private $strategy;

  public function setStrategy(FormatStrategy $strategy): void
  {
    $this->strategy = $strategy;
  }

  public function format(string $message): string
  {
    if (null === $this->strategy) {
      throw new RuntimeException('Missing strategy');
    }

    return $this->strategy->execute($message);
  }
}


interface FormatStrategy
{
  public function execute(string $message): string;
}


class HtmlFormatStrategy implements FormatStrategy
{
  public function execute(string $message): string
  {
    return '<html><body><h1>' . $message . '</h1></body></html>';
  }
}


class MarkdownFormatStrategy implements FormatStrategy
{
  public function execute(string $message): string
  {
    return '# ' . $message;
  }
}

$context = new FormatContext();

$context->setStrategy(new HtmlFormatStrategy());
echo $context->format('Hi!');

$context->setStrategy(new MarkdownFormatStrategy());
echo $context->format('Hi!');
