<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\News;

use App\Infrastructure\Http\Common\Action\News\ListNews;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\EmployeeBuilder;
use App\Tests\Builder\NewsBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(ListNews::class)]
final class ListNewsTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $employee = $this->getService(EmployeeBuilder::class)->build();
        $otherAuthor = $this->getService(EmployeeBuilder::class)->build();
        $newsBuilder = $this->getService(NewsBuilder::class);
        $newsBuilder->build();
        $newsBuilder->withAuthor($otherAuthor)->asPublished()->build();

        // prettier-ignore
        $bigContent =
            'Вот вам яркий пример современных тенденций — начало повседневной работы по формированию позиции представляет собой интересный эксперимент проверки новых принципов формирования материально-технической и кадровой базы. Принимая во внимание показатели успешности, повышение уровня гражданского сознания обеспечивает актуальность экспериментов, поражающих по своей масштабности и грандиозности. Внезапно, тщательные исследования конкурентов освещают чрезвычайно интересные особенности картины в целом, однако конкретные выводы, разумеется, подвергнуты целой серии независимых исследований. Предварительные выводы неутешительны: сложившаяся структура организации требует определения и уточнения приоретизации разума над эмоциями. Наше дело не так однозначно, как может показаться: реализация намеченных плановых заданий, а также свежий взгляд на привычные вещи — безусловно открывает новые горизонты для существующих финансовых и административных условий. Есть над чем задуматься: многие известные личности описаны максимально подробно. Идейные соображения высшего порядка, а также повышение уровня гражданского сознания говорит о возможностях поставленных обществом задач. Следует отметить, что синтетическое тестирование требует от нас анализа поэтапного и последовательного развития общества. Предварительные выводы неутешительны: понимание сути ресурсосберегающих технологий требует от нас анализа поставленных обществом задач. Также как укрепление и развитие внутренней структуры выявляет срочную потребность форм воздействия.';
        $newsBuilder->withAuthor($employee)->withContent($bigContent)->build();

        $response = $this->httpRequest(method: Request::METHOD_GET, url: '/listNews')
            ->withAuthentication($employee)
            ->withQuery([
                'pagination' => [
                    'count' => 10,
                    'offset' => 0,
                ],
                'orderByDate' => 'desc',
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
        $this->assertCountOfItemsInResponse(response: $response, expectedCount: 2);
    }
}
