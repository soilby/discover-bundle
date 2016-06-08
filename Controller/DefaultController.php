<?php

namespace Soil\DiscoverBundle\Controller;

use Doctrine\ORM\Internal\Hydration\ObjectHydrator;
use EasyRdf\Literal;
use Soil\DiscoverBundle\Entity\Generic;
use Soil\DiscoverBundle\Service\Resolver;
use Symfony\Component\HttpFoundation\Response;

class DefaultController
{
    protected $resolver;
    protected $templating;

    public function __construct(Resolver $resolver, $templating) {
        $this->resolver = $resolver;
        $this->templating = $templating;
    }

    protected function dumpEntity($entities)  {

            $data = [];


            if (is_object($entities)) {

                    $reflectionClass = new \ReflectionClass($entities);
                    $methods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);

                    $hash = [];
                    foreach ($methods as $method) {
                        $methodName = $method->getName();
                        if ($methodName === 'getValues') continue;
                        if (strpos($methodName, 'get') === 0 && ctype_upper($methodName[3])) {
                            $value = $method->invoke($entities);
                            $hash[$methodName] = $value;
                        }

                    }


                if (method_exists($entities, 'getValues')) {
                    $values = $entities->getValues();

//                    $entities = $hash + json_encode($values);
                    $hash['getValues'] = json_encode($values);
                    $entities = $hash;
                }
                else    {
                    $entities = $hash;
                }




            }

            foreach ($entities as $name => $element) {
                if (is_object($element)) {
                    switch (true) {
                        case $element instanceof Literal\DateTime:
                        case $element instanceof Literal\Date:
                            $value = $element->getValue()->format('Y-m-d H:i:s');
                            break;

                        case $element instanceof Literal:
                            $value = $element->getValue();
                            break;

                        default:
                            $value = $this->dumpEntity($element);

                            break;
                    }
                    $class = get_class($element);

                    $data[] = [
                        'class' => $class,
                        'name' => $name,
                        'value' => $value
                    ];
                } else {
                    $data[] = [
                        'name' => $name,
                        'value' => $element,
                        'class' => null
                    ];
                }
            }

            //        if (!is_array($entities))   {
            //            return current($data);
            //        }
            //        else    {
            return $data;
            //        }


    }

    public function printMe($object)    {
        foreach ($object as $key => $element) {

            echo $element['name'];

            echo ' ';
            if (is_array($element['value']))  {
                $this->printMe($element['value']);
            }
            else    {
                echo($element['value']);

            }

            if ($element['class']) {
                echo ' [' . $element['class'] . ']';
            }

            echo '<hr>';

        }
    }

    public function discoverAction($entity_uri)
    {
        try {
            $expected_class = true;
            $entitiesArr = $this->resolver->getEntityForURI($entity_uri, $expected_class);
    //        var_dump($entitiesArr);

            if (is_object($entitiesArr))    {
                $class = get_class($entitiesArr);
            }
            else    {
                $class = '';
            }

            $data = $this->dumpEntity($entitiesArr, $expected_class);
echo '<pre>';
            $this->printMe($data);
            
            echo '<hr />';
            
            echo $this->resolver->getLastGraph()->dump();

exit;
            $content = $this->templating->render('SoilDiscoverBundle:Default:index.html.twig', [
                'class' => $class,
                'graph' => $data,
                'uri' => $entity_uri
            ]);

            return new Response($content);
        }
        catch(\Exception $e)    {
            echo $e;
            exit();
        }
    }
}
