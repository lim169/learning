<?php

namespace App\CourseBundle\Repository;

use App\CourseBundle\Entity\Choose;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ChooseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ChooseRepository extends \Doctrine\ORM\EntityRepository
{

  /**
   * @param array   $nodes Request data
   */
  public function save($data, $question) {
      $query = new Choose();
      $query->setQuestion($question);
      $query->setStatus($data["answer"]);
      $query->setAnswer($data["answer"]);

      $this->_em->persist($query);
      $this->_em->flush();
  }


  /**
  * @return \Doctrine\ORM\Query
  */
  public function findChooses($id = null) {
    $query = $this->findByIdQb($id);

    return $query->getQuery();
  }

  /**
  * Return QueryBuilder for choose by question id
  *
  * @return \Doctrine\ORM\QueryBuilder
  */
  public function findByIdQb($id = null) {
    if ($id == null) {
      throw new BadRequestHttpException("No id provided");
    }

    $query = $this->findSimpleQuestion()
      ->leftJoin('choose.question', 'question')
      ->addSelect('question')
      ->andWhere('choose.question = :id')
      ->setParameter(':id', $id);

    return $query;
  }

  /**
  * @return \Doctrine\ORM\QueryBuilder
  */
  private function findSimpleQuestion() {
    $query = $this->createQueryBuilder('choose')
      ->select('choose');

    return $query;
  }

}
