<?php
namespace App\CourseBundle\Repository;

use App\CourseBundle\Entity\Question;
use App\CourseBundle\Entity\Image;
use App\CourseBundle\Entity\Choose;
use App\CourseBundle\Entity\Pattern;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use DoctrineExtensions\Query\Mysql;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
* QuestionRepository
*
* This class was generated by the Doctrine ORM. Add your own custom
* repository methods below.
*/
class QuestionRepository extends \Doctrine\ORM\EntityRepository {

  /**
   * @param array   $nodes Request data
   */
  public function save($data, $course) {
      $question = new Question();
      $question->setCourse($course);
      $question->setType($data["type"]);
      $question->setTitle($data["title"]);
      $question->setAnswer($data["answer"]);
      $question->setDifficulty($data["difficulty"]);
      $question->setQuestion($data["question"]);
      $question->setChoose($data["choose"]);
      $question->setImage($data["image"]);
      $question->setSound($data["sound"]);

      $this->_em->persist($question);
      $this->_em->flush();

      return $question;
  }

  /**
  * @param integer $id Course id
  *
  * @return \Doctrine\ORM\Query
  */
  public function countAllByCourse($id = null) {
    $query = $this->countAllQuestionsQb($id);

    return $query->getQuery();
  }

  /**
  * Return QueryBuilder for full data
  *
  * @return \Doctrine\ORM\QueryBuilder
  */
  private function countAllQuestionsQb($id = null) {
    if ($id == null) {
      throw new BadRequestHttpException("No id provided");
    }

    $query = $this->findSimpleQuestion()
      ->andWhere('q.course = :course')
      ->setParameter(':course', $id)
      ->select('count(q.id)');

    return $query;
  }

  /**
  * @param integer $id Course id
  *
  * @return \Doctrine\ORM\Query
  */
  public function findIdsByCourse($id = null) {
    $query = $this->findAllQuestionsIdQb($id);

    return $query->getQuery();
  }

  /**
  * Return QueryBuilder for full data
  *
  * @return \Doctrine\ORM\QueryBuilder
  */
  private function findAllQuestionsIdQb($id = null) {
    if ($id == null) {
      throw new BadRequestHttpException("No id provided");
    }

    $query = $this->findSimpleQuestion('q.id')
      ->andWhere('q.course = :course')
      ->setParameter(':course', $id);

    return $query;
  }

  /**
   * @param int $id
   * @return Question[]
   */
  public function findRandom($id = null) {
    $query = $this->findRandomQb($id);

    return $query->getQuery();
  }

  /**
  * Return QueryBuilder for random data
  *
  * @return \Doctrine\ORM\QueryBuilder
  */
  public function findRandomQb($id = null) {
    if ($id == null) {
      throw new BadRequestHttpException("No id provided");
    }

    $query = $this->findSimpleQuestion($this->fields())
      ->andWhere('q.course = :course')
      ->setParameter(':course', $id)
      ->orderBy('RAND()')
      ->setMaxResults(1);

    return $query;
  }

  /**
  * Return QueryBuilder
  *
  * @return \Doctrine\ORM\QueryBuilder
  */
  public function findChoosesById($id = null) {
    if ($id == null) {
      throw new BadRequestHttpException("No id provided");
    }

    $query = $this->findSimpleQuestion('q')
      ->andWhere('q.id = :id')
      ->setParameter(':id', $id)
      ->leftJoin('q.chooses', 'chooses')
      ->addSelect('chooses');

    return $query->getQuery();
  }

  /**
  * Return QueryBuilder
  *
  * @return \Doctrine\ORM\QueryBuilder
  */
  public function findPatternsById($id = null) {
    if ($id == null) {
      throw new BadRequestHttpException("No id provided");
    }

    $query = $this->findSimpleQuestion('q')
      ->andWhere('q.id = :id')
      ->setParameter(':id', $id)
      ->leftJoin('q.patterns', 'patterns')
      ->addSelect('patterns');

    return $query->getQuery();
  }

  /**
  * @return \Doctrine\ORM\Query
  */
  public function findById($id = null) {
    $query = $this->findByIdQb($id);

    return $query->getQuery();
  }

  /**
  * Return QueryBuilder for question by id
  *
  * @return \Doctrine\ORM\QueryBuilder
  */
  public function findByIdQb($id = null) {
    if ($id == null) {
      throw new BadRequestHttpException("No id provided");
    }

    $query = $this->findSimpleQuestion($this->fields())
      ->andWhere('q.id = :id')
      ->setParameter(':id', $id);

    return $query;
  }

  /**
  * @return \Doctrine\ORM\QueryBuilder
  */
  private function findSimpleQuestion($fields = null) {
    $query = $this->createQueryBuilder('q')
      ->select($fields);

    return $query;
  }

  private function fields() {
    return array(
      'q.id',
      'q.title',
      'q.sound',
      'q.difficulty',
      'q.type',
      'q.question',
      'q.answer',
      'q.choose',
      'q.image'
    );
  }

}
